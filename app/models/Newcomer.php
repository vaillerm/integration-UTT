<?php

class Newcomer extends Eloquent {

    public $table = 'newcomers';
    public $timestamps = true;

    public $primaryKey = 'id';

    public function getDates()
    {
        return ['created_at', 'update_at', 'birth'];
    }

    public $fillable = [
        'first_name',
        'last_name',
        'password',
        'sex',
        'email',
        'phone',
        'birth',
        'level',
        'referral',
        'team'
    ];

    /**
     * Define the One-to-One relation with Team.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function team()
    {
        return $this->belongsTo('Team');
    }

    /**
     * Define the One-to-One relation with Referral.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function referral()
    {
        return $this->belongsTo('Referral');
    }

    /**
     * @return string
     */
    public function fullName()
    {
        return $this->first_name . ' ' . $this->last_name;
    }
}
