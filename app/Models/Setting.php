<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Config;

class Setting extends Model
{
    public $timestamps = false;

    static function getAvailableSetting()
    {
        return Config::all();
    }
}
