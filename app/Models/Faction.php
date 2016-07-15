<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Faction extends Model
{

    /**
     * @var string
     */
    public $table = 'factions';

    /**
     * @var boolean
     */
    public $timestamps = false;

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function teams()
    {
        return $this->hasMany('App\Models\Team');
    }
}
