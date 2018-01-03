<?php

namespace Tchoblond59\LaraLight\Models;

use Illuminate\Database\Eloquent\Model;

class PeriodConfig extends Model
{
    public $table = 'll_periods_configs';

    public $timestamps = false;

    public function period()
    {
        return $this->belongsTo('Tchoblond59\LaraLight\Models\Period', 'll_periods_levels_id');
    }
}
