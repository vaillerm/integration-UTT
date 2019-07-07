<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Device extends Model
{
    public $table = 'devices';
    public $timestamps = true;

    public $primaryKey = 'id';

    public $fillable = [
        'name',
        'uid',
        'push_token',
        'user_id',
    ];

    public $hidden = [
        'created_at',
        'updated_at',
    ];

    public function user()
    {
        return $this->belongsTo('App\User');
    }
}
