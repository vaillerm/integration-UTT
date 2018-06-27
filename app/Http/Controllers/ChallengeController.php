<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use EtuUTT;
use View;
use DB;
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
		if(!EtuUTT::student()->isAdmin()) {
			throw $this->error("Vous n'avez pas le droit d'accéder à cette page.");
		}
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
	}

	public function showChallengesList() {
		$challenges = DB::table("challenges")->get();
		return View::make('dashboard.challenges.list', [
			"challenges" => $challenges
		]);
	}
}
