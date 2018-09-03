<?php

namespace App\Models;

use App\Models\Team;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;

class Challenge extends Model {

    public $timestamps=false;

    public $fillable = [
        'name',
        'description',
        'points',
        'deadline',
        'for_newcomer'
    ];

    /**
     * @return true whether the challenges deadline has passed or not
     */
    public function deadlineHasPassed() : bool {
        $challenge_date = new \DateTime($this->deadline);
        /**P1D => Plus 1 Day
         * one day is added so
         * if the deadline is the 2018-08-31
         * that include the 31
         */
        $challenge_date->add(new \DateInterval("P1D"));
        $now = new \DateTime('now');
        return !($challenge_date > $now);
    }

    public function teamValidable(Team $team) : bool {
        return !($team->hasAlreadyValidatedChallenge($this->id) || $this->deadlineHasPassed() || $team->hasPendingValidationForChallenge($this));
    }

    public function newComerValidable(User $user) : bool {
        if($this->for_newcomer) {
            return !($user->hasAlreadyValidatedChallenge($this->id) && !$this->deadlineHasPassed());
        }
        else
            return false;
    }


    /**
     * All the teams that asked validation for this challenge
     * whether it is accepted or not
     */
    public function teams() {
        /**
         * validated can have 3 values :
         *  -1: refused
         *  0: pending
         *  1: accepted
         *  Yep, a boolean should have been used, but my first solution was to use
         *  false: refused
         *  true: accepted
         *  null: pending
         *  and laravel (at least in 5.2) doesn't seem to differenciate null and false
         */
        $pivots = ['submittedOn', 'validated', 'proof_url', 'last_update', 'update_author', 'message'];
        return $this->belongsToMany('App\Models\Team', 'challenge_validations')->withPivot($pivots);
    }

}
