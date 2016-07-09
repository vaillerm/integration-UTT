<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Student extends Model {

    public $table = 'students';
    public $timestamps = true;

    const SEX_MALE = 0;
    const SEX_FEMALE = 1;

    const ADMIN_NOT = 0;
    const ADMIN_FULL = 100;

    public function getDates()
    {
        return ['created_at', 'updated_at', 'last_login', 'referral_validated_at'];
    }

    public $primaryKey = 'student_id';

    public $fillable = [
        'student_id',
        'first_name',
        'last_name',
        'sex',
        'surname',
        'email',
        'phone',
        'postal_code',
        'city',
        'country',
        'branch',
        'level',
        'referral_text',
        'referral_max',
        'volunteer',
        'facebook',
    ];

    public $hidden = [
        'created_at',
        'updated_at',
    ];

    /**
     * Define the One-to-Many relation with Newcomer;
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function newcomers()
    {
        return $this->hasMany('App\Models\Newcomer');
    }

    /**
     * Define the One-to-Many relation with Newcomer;
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function team()
    {
        return $this->belongsTo('App\Models\Team');
    }

    /**
     * Test if the student is a validated referral
     * @return bool
     */
    public function isValidatedReferral() {
        return ($this->referral_validated_at != null);
    }

    /**
     * Test if the student can access part of the dashboard
     * @return bool
     */
    public function hasDashboard() {
        return ($this->admin != Student::ADMIN_NOT);
    }

    /**
     * Test if the student can all of the dashboard
     * @return bool
     */
    public function isAdmin() {
        return ($this->admin == Student::ADMIN_FULL);
    }

}
