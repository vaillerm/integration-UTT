<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MailHistory extends Model
{
    protected $fillable = [
        'user_id',
        'mail_template_id',
        'mail',
        'mail_cron_id'
    ];
}
