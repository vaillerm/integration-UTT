<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;
use View;
use DB;
use App\Models\Challenge;
use App\Http\Requests\ChallengeRequest;

class ChallengeController extends Controller
{
	/**
	 * Allow an admin to add a challenge
	 */
	public function AddChallengeForm() {
		return View::make('dashboard.challenges.add');
	}

	public function ModifyChallengeForm(int $challengeId) {
		$challenge = Challenge::find($challengeId);
		return view("dashboard.challenges.modify", compact("challenge"));
	}

	/**
	 * Convert the ChallengeRequest object to an array
	 * which contains all the info to create or update a challenge.
	 * it is usefull in laravel 5.2, since you can't retrieve an array
	 * from the request directly, or I did not find a way :|
	 */
	private function challengeRequest2Array(ChallengeRequest $request):array {
		return [
			"name" => $request->name,
			"description" => $request->description,
			"points" => $request->points,
			"deadline" => $request->deadline
		];
	}

	/**
	 * Modify a challenge
	 */
	public function modify(ChallengeRequest $request, int $challengeId) {
		$challenge_to_update = Challenge::find($challengeId);
		$challenge_to_update->update($this->challengeRequest2Array($request));
		return redirect(route("challenges.list"));
	}



	/**
	 * Add a challenge
	 */
	public function add(ChallengeRequest $request) {
		$challenge = $this->challengeRequest2Array($request);
		Challenge::create($challenge);
		$request->session()->flash('success', 'Défis ajouté');
		return redirect("challenges/");
	}

	/**
	 * Used by team leader to submit a challenge to be validated
	 */
	public function submitChallenge(int $idChallenge) {
		$challenge = DB::table('challenges')->where("id", "=", $idChallenge)->first();
		return View::make("dashboard.challenges.submit", [
			"challenge" => $challenge
		]);
	}

	public function uploadProof(Request $request) {

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
