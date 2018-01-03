<?php

namespace Tchoblond59\LaraLight\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Period extends Model
{
    public $table = 'll_periods_levels';
    public $timestamps = false;

    //protected $dates = ['from', 'to'];

    public function getFromAttribute($value)
    {
        return Carbon::createFromFormat('H:i:s', $value);
    }

    public function getToAttribute($value)
    {
        return Carbon::createFromFormat('H:i:s', $value);
    }
}
