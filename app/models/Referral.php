<?php

class Referral extends Eloquent {

    public $table = 'referrals';
    public $timestamps = true;

    public function getDates()
    {
        return ['created_at', 'updated_at', 'started_validation_at'];
    }

    public $primaryKey = 'student_id';

    public $fillable = [
        'student_id',
        'first_name',
        'last_name',
        'surname',
        'email',
        'phone',
        'postal_code',
        'city',
        'country',
        'level',
        'free_text',
        'max',
        'double_degree',
    ];

    public $hidden = [
        'created_at',
        'updated_at',
        'free_text',
        'started_validation_at',
        'referral'
    ];

    /**
     * Define the One-to-Many relation with Newcomer;
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function newcomers()
    {
        return $this->hasMany('Newcomer');
    }

}
