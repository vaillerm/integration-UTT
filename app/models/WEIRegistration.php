<?php

class WEIRegistration extends Eloquent {

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'wei_registrations';

    public $timestamps = true;

    public $fillable = [
        'first_name',
        'last_name',
        'phone',
        'email',
        'gave_parental_authorization',
        'birthdate'
    ];

    /**
     * Primary key of the table.
     *
     * @var string
     */
    public $primaryKey = 'id';

    /**
     * Columns to consider as dates (timestamps used by Carbon).
     *
     * @var string
     */
    public function getDates()
    {
        return ['created_at', 'updated_at', 'birthdate'];
    }

    public function deposit()
    {
        return $this->belongsTo('Payment');
    }

    public function payment()
    {
        return $this->belongsTo('Payment');
    }
}
