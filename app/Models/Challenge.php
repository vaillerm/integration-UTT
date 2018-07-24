<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Challenge extends Model {

	public $timestamps=false;

	public $fillable = [
		"name",
		"description",
		"points",
		"deadline"
	];

	/**
	 * All the teams that asked validation for this challenge
	 */
	public function teams() {
		return $this->belongsToMany("App\Models\Team", "challenge_validations")->withPivot(["submittedOn", "validated", "pic_url"]);
	}

}
