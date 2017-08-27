<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    public $table = 'events';
    public $timestamps = true;

    public $primaryKey = 'id';

    public $fillable = [
        'name',
        'description',
        'place',
        'categories',
        'start_at',
        'end_at'
    ];
}
