<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ChallengeValidation extends Model
{
    protected $table='challenge_validations';
    public $timestamps=false;
    protected $primary = 'id';
    protected $fillable = [
        'validated',
        'last_update',
        'update_author',
        'message',
        'submittedOn',
        'user_id',
        'adjustment',
    ];

    public function user() {
        return $this->belongsTo("App\Models\User");
    }

    /**
     * Return an array with the css class and the content of what to display
     * like [ 'css' => 'class', 'content' => text ]
     *
     */
    public function prettyStatus() : array{
        $common_css_to_all = 'label label-';
        switch($this->validated) {
        case 1: 
            return [
                'css' => $common_css_to_all.'success',
                'content' => 'validé'
            ];
            break;
        case -1: 
            return [
                'css' => $common_css_to_all.'danger',
                'content' => 'refusé'
            ];
            break;
        case 0: 
            return [
                'css' => $common_css_to_all.'warning',
                'content' => 'en attente...'
            ];
            break;
        }

    }

    public function retryPossible() :bool {
        $should_be_empty = $this->teams()->first()->challenges()->wherePivot("validated", "=", 1)->get();
        $deadlinePassed = $this->challenges()->first()->deadlineHasPassed();
        return count($should_be_empty) < 1 && !$deadlinePassed;

    }

    public function update_author() 
    {
        return $this->hasMany('App\Models\User', 'id', 'update_author');
    }

    public function teams() 
    {
        return $this->hasMany('App\Models\Team', 'id', 'team_id');
    }

    public function challenges() 
    {
        return $this->hasMany('App\Models\Challenge', 'id', 'challenge_id');
    }

}
