<?php

namespace PluginTests;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Mockery;
use PhpMqtt\Client\MqttClient;

abstract class TestCase extends \Tests\TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $client = Mockery::mock(MqttClient::class);
        $client->shouldNotReceive('connect');
        $this->app->instance(MqttClient::class, $client);
    }

    protected function expectPublications(array $messages): void
    {
        config(['mqtt.topic' => 'plugin-tests']);
        $client = Mockery::mock(MqttClient::class);
        $client->shouldReceive('connect')->times(count($messages));
        foreach ($messages as [$topic, $payload]) {
            $client->shouldReceive('publish')->once()->ordered()->with($topic, $payload, 0, false);
        }
        $client->shouldReceive('disconnect')->times(count($messages));
        $this->app->instance(MqttClient::class, $client);
    }

    protected function widget(\App\Sensor $sensor): \App\Widget
    {
        $user = \App\User::create(['name' => 'Plugin operator', 'email' => 'operator@example.test', 'password' => bcrypt('test-only')]);
        $this->actingAs($user);
        $dashboard = new \App\Dashboard();
        $dashboard->name = 'Plugin dashboard';
        $dashboard->user_id = $user->id;
        $dashboard->save();
        $widget = new \App\Widget();
        $widget->name = 'Plugin widget';
        $widget->sensor_id = $sensor->id;
        $widget->dashboard_id = $dashboard->id;
        $widget->save();
        return $widget;
    }

    protected function sensor(string $class, int $address = 1): \App\Sensor
    {
        return \App\Sensor::create([
            'node_address' => 42, 'sensor_address' => $address,
            'name' => 'Plugin test', 'classname' => $class,
        ]);
    }
}
