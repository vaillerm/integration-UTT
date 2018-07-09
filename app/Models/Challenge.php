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

	public function __construct() {

	}

}
