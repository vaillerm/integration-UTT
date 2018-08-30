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

    public function scord() {
      $score = 0;
      foreach($this->teams() as $team){
        $score = $score + $team->score();
      }
      return $score;
    }
}
