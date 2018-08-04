<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MailCron extends Model
{
    public function mail_template()
    {
        return $this->belongsTo(MailTemplate::class);
    }
}
