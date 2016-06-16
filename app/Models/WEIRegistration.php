<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WEIRegistration extends Model {

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
        'birthdate',
        'is_orga',
        'allergy'
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

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function deposit()
    {
        return $this->belongsTo('App\Models\Payment');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function payment()
    {
        return $this->belongsTo('App\Models\Payment');
    }
}
