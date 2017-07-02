<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Config;

class Setting extends Model
{
    static function getAvailableSetting()
    {
        return Config::all();
    }
}
