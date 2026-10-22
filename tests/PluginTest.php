<?php

namespace PluginTests;

use Carbon\Carbon;
use Illuminate\Support\Facades\Event;
use Tchoblond59\LaraLight\Events\LaraLightEvent;
use Tchoblond59\LaraLight\Models\LaraLight;
use Tchoblond59\LaraLight\Models\LaraLightConfig;

class PluginTest extends TestCase
{
    protected function tearDown(): void
    {
        Carbon::setTestNow();
        parent::tearDown();
    }

    private function light(?int $minutes = null, int $address = 1): LaraLight
    {
        $sensor = $this->sensor(LaraLight::class, $address);
        $config = new LaraLightConfig();
        $config->forceFill(['relay_id' => $sensor->id, 'mode' => 'auto', 'state' => 1, 'delay' => 30,
            'on_since' => $minutes === null ? null : Carbon::now()->subMinutes($minutes)]);
        $config->save();
        return LaraLight::findOrFail($sensor->id);
    }

    public function testLevelAndForcedStatusKeepMqttProtocolAndUpdateState(): void
    {
        Event::fake([LaraLightEvent::class]);
        $light = $this->light();
        $this->expectPublications([
            ['plugin-tests/42/1/1/1/3', '50'],
            ['plugin-tests/42/1/1/1/2', '1'],
        ]);
        $light->setLevel(50);
        $this->assertSame(50, (int) $light->config->fresh()->state);
        $this->assertInstanceOf(Carbon::class, $light->config->on_since);
        $light->setLevel(1, true);
        $this->assertSame(1, (int) $light->config->fresh()->state);
        Event::assertDispatchedTimes(LaraLightEvent::class, 2);
    }

    public function testAutoOffOnlySwitchesExpiredLights(): void
    {
        Event::fake([LaraLightEvent::class]);
        Carbon::setTestNow(Carbon::parse('2026-10-22 12:00:00'));
        $expired = $this->light(31, 1);
        $recent = $this->light(29, 2);
        $unknown = $this->light(null, 3);
        $this->expectPublications([['plugin-tests/42/1/1/1/3', '0']]);
        $this->artisan('laralight:auto_switch_off')->assertExitCode(0);
        $this->assertSame(0, (int) $expired->config->state);
        $this->assertSame(1, (int) $recent->config->state);
        $this->assertSame(1, (int) $unknown->config->state);
    }

    public function testAnonymousRequestIsRedirectedToLogin(): void
    {
        $this->post('/LaraLight/action/setLevel')->assertRedirect('/login');
    }

    public function testInvalidLevelCannotBeSentToHardware(): void
    {
        $light = $this->light();
        $widget = $this->widget($light);
        $this->postJson('/LaraLight/action/setLevel', ['id' => $widget->id, 'sensor_id' => $light->id, 'level' => '500'])
            ->assertStatus(422);
    }
}
