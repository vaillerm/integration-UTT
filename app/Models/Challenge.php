<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Challenge extends Model {

    public $timestamps=false;

    public $fillable = [
        'name',
        'description',
        'points',
        'deadline'
    ];

    /**
     * @return true whether the challenges deadline has passed or not
     */
    public function deadlineHasPassed() : bool {
        $challenge_date = new \DateTime($this->deadline);
        $now = new \DateTime('now');
        return !($challenge_date > $now);
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
        $pivots = ['submittedOn', 'validated', 'pic_url', 'last_update', 'update_author', 'message'];
        return $this->belongsToMany('App\Models\Team', 'challenge_validations')->withPivot($pivots);
    }

}
