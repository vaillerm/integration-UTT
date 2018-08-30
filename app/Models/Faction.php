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

    public function score() : int {
        DB::table("factions")
            ->join("teams",  "factions.team_id", "=", "teams.id")
            ->joint("challenges_validation", "challenge_validations.team_id", "=", "teams.id")
            ->join("challenges", "challenges")
            ;

        return $score;

    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function teams()
    {
        return $this->hasMany('App\Models\Team');
    }
}
