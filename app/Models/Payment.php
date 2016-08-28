<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'payments';

    public $fillable = [
        'type',
        'mean',
        'amount',
        'state',
        'informations'
    ];

    public $timestamps = true;

    protected $casts = [
        'informations' => 'array',
    ];


    /**
     * Define the One-to-One relation with Newcomer
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function newcomerWei()
    {
        return $this->hasOne('App\Models\Newcomer', 'wei_payment');
    }

    /**
     * Define the One-to-One relation with Newcomer
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function newcomerSandwich()
    {
        return $this->hasOne('App\Models\Newcomer', 'sandwich_payment');
    }

    /**
     * Define the One-to-One relation with Newcomer
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function newcomerGuarantee()
    {
        return $this->hasOne('App\Models\Newcomer', 'guarantee_payment');
    }

    public function newcomer()
    {
        if ($this->newcomerSandwich) {
            return $this->newcomerSandwich();
        }
        if ($this->newcomerGuarantee) {
            return $this->newcomerGuarantee();
        }
        return $this->newcomerWei();
    }

    public function paymentByDay()
    {
        return static::select('id')
            ->get()
            ->where('type', 'payment')
            ->andWhere('state', 'paid')
            ->groupBy(function ($date) {
                return Carbon::parse($date->created_at)->format('d'); // grouping by months
            });
    }


    /**
     * Define the One-to-One relation with Student
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function studentWei()
    {
        return $this->hasOne('App\Models\Student', 'wei_payment');
    }

    /**
     * Define the One-to-One relation with Student
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function studentSandwich()
    {
        return $this->hasOne('App\Models\Student', 'sandwich_payment');
    }

    /**
     * Define the One-to-One relation with Student
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function studentGuarantee()
    {
        return $this->hasOne('App\Models\Student', 'guarantee_payment');
    }

    public function student()
    {
        if ($this->studentSandwich) {
            return $this->studentSandwich();
        }
        if ($this->studentGuarantee) {
            return $this->studentGuarantee();
        }
        return $this->studentWei();
    }
}
