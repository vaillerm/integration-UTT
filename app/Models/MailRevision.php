<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\View\View;

class MailRevision extends Model
{
    public function generateHtml(Student $user=null, $mail_id = null)
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
            foreach ($user_dot as $key=>$value)
            {
                $content = str_replace('%'.$key.'%', $value, $content);
            }
        }
        return view('emails.template.'.$this->template, [
            'subject' => $this->subject,
            'content'=> $content,
            'user' => $user,
            'mail' => $this,
            'mail_id' => $mail_id,
            'unsuscribe_link' => url()->route('emails.unsubscribe', ['email' => $user->email])
        ])->render();
    }

}
