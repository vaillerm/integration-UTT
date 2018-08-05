<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ChallengeValidation extends Model
{
    protected $table="challenge_validations";
    public $timestamps=false;
    protected $primary = ["team_id", "challenge_id"];

    /**
     * Return an array with the css class and the content of what to display
     * like [ "css" => "class", "content" => text ]
     *
     */
    public function prettyStatus() : array{
        $common_css_to_all = "label label-";
        switch($this->validated) {
        case 1: 
            return [
                "css" => $common_css_to_all."success",
                "content" => "validé"
            ];
            break;
        case -1: 
            return [
                "css" => $common_css_to_all."danger",
                "content" => "refusé"
            ];
            break;
        case 0: 
            return [
                "css" => $common_css_to_all."warning",
                "content" => "en attente..."
            ];
            break;
        }

    }

    public function update_author() 
    {
        return $this->hasMany("App\Models\User", "id", "update_author");
    }

    public function teams() 
    {
        return $this->hasMany("App\Models\Team", "id", "team_id");
    }

    public function challenges() 
    {
        return $this->hasMany("App\Models\Challenge", "id", "challenge_id");
    }

}
