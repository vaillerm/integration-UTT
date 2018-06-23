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
     * Define the One-to-One relation with User
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function userWei()
    {
        return $this->hasOne('App\Models\User', 'wei_payment');
    }

    /**
     * Define the One-to-One relation with User
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function userSandwich()
    {
        return $this->hasOne('App\Models\User', 'sandwich_payment');
    }

    /**
     * Define the One-to-One relation with User
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function userGuarantee()
    {
        return $this->hasOne('App\Models\User', 'guarantee_payment');
    }

    public function user()
    {
        if ($this->userSandwich) {
            return $this->userSandwich();
        }
        if ($this->userGuarantee) {
            return $this->userGuarantee();
        }
        return $this->userWei();
    }
}
