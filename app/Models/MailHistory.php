<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MailHistory extends Model
{
    protected $fillable = [
        'student_id',
        'mail_revision_id',
        'mail',
        'mail_cron_id'
    ];
}
