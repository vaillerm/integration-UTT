<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ChallengeValidation extends Model
{
	protected $table="challenge_validations";
	public $timestamps=false;
	protected $primary = ["team_id", "challenge_id"];

	public function teams() 
	{
		return $this->hasMany("App\Models\Team", "id", "team_id");
	}

	public function challenges() 
	{
		return $this->hasMany("App\Models\Challenge", "id", "challenge_id");
	}

}
