<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use EtuUTT;
use View;
use App\Models\Challenge;

class ChallengeController extends Controller
{
	/**
	 * Allow an admin to add a challenge
	 * @Return null
	 */
	public function displayForm() {
		if(!EtuUTT::student()->isAdmin()) {
			return $this->error('Vous n\'avez pas le droit d\'accéder à cette page.');
		}
		return View::make('dashboard.challenges.add');
	}

	public function addChallenge(Request $request) {
		$this->validate($request, [
			"name" => "required|unique:challenges|max:30",
			"description" => "required|max:140",
		]);

		$challenge = new Challenge();
		$challenge->name = $request->name;
		$challenge->description = $request->description;
		$challenge->points = $request->points;
		$challenge->deadline = $request->deadline;

		$challenge->save();
		echo "Ok lol";
	}
}
