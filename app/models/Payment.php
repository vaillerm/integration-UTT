<?php

class Payment extends Eloquent {

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
