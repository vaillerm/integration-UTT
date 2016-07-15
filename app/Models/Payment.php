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
        'mean',
        'amount',
        'bank',
        'number',
        'emitter'
    ];

    public $timestamps = false;
}
