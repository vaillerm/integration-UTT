<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Student extends Model
{

    public $table = 'students';
    public $timestamps = true;

    const SEX_MALE = 0;
    const SEX_FEMALE = 1;

    const ADMIN_NOT = 0;
    const ADMIN_FULL = 100;

    public function getDates()
    {
        return ['created_at', 'updated_at', 'last_login'];
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
        'team_id',
        'ce'
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
        return $this->hasMany('App\Models\Newcomer', 'referral_id');
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
     * Test if the student can all of the dashboard
     * @return bool
     */
    public function isAdmin()
    {
        return ($this->admin == Student::ADMIN_FULL);
    }


    /**
     * Define the One-to-One relation with Payment
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function weiPayment()
    {
        return $this->belongsTo('App\Models\Payment', 'wei_payment');
    }

    /**
     * Define the One-to-One relation with Payment
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function sandwichPayment()
    {
        return $this->belongsTo('App\Models\Payment', 'sandwich_payment');
    }

    /**
     * Define the One-to-One relation with Payment
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function guaranteePayment()
    {
        return $this->belongsTo('App\Models\Payment', 'guarantee_payment');
    }


    public function updateWei()
    {
        $weiPayment = $this->weiPayment && in_array($this->weiPayment->state, ['paid', 'returned']);
        $guaranteePayment = $this->guaranteePayment && in_array($this->guaranteePayment->state, ['paid', 'returned']);

        $wei = ($weiPayment || $guaranteePayment);
        if ($this->wei != $wei) {
            $this->wei = $wei;
        }
        $this->save();
    }
}
