<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\View\View;

class MailTemplate extends Model
{

    public $fillable = [
        'subject',
        'content',
        'template',
        'isPublicity',
    ];

    public function generateHtml(User $user=null, $mail_id = null)
    {
        if(!$user)
        {
            $user = User::first();
        }

        $user = $user->load(['team', 'godFather']);

        $user_array = $user->toArray();
        $user_dot = array_dot($user_array);

        $content = '';

        if($this->content) {
            $content = $this->content;
            foreach ($user_dot as $key => $value)
            {
                if (!is_array($value)) {
                    $content = str_replace('%'.$key.'%', $value, $content);
                }
            }
        }
        return view('emails.template.'.$this->template, [
            'subject' => $this->subject,
            'content'=> $content,
            'user' => $user,
            'mail' => $this,
            'mail_id' => $mail_id,
            'unsuscribe_link' => url()->route('emails.unsubscribe', ['email' => $user->getBestEmail()])
        ])->render();
    }

    /**
     * Define the One-to-Many relation with User
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function createdBy()
    {
        return $this->belongsTo('App\Models\User');
    }
}
