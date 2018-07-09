<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;
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
		$request->session()->flash('success', 'Défis ajouté.');
		return redirect("challenges/");
	}

	public function deleteChallenge(int $idChallenge) {
		DB::table("challenges")->where("id", "=", $idChallenge)->delete();
		return redirect("challenges/");
	}

	public function showChallengesList() {
		$challenges = DB::table("challenges")->get();
		return View::make('dashboard.challenges.list', [
			"challenges" => $challenges
		]);
	}
}
