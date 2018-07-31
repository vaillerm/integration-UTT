<?php

namespace App\Http\Controllers\Challenges;

use Illuminate\Http\Request;
use Auth;
use View;
use App\Models\Team;
use DB; use Storage;
use App\Models\Challenge;
use App\Http\Requests\ChallengeRequest;
use App\Http\Controllers\Controller;
use App\Models\ChallengeValidation;

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

	public function refuseForm(int $challengeId, int $teamId) {
		return view("dashboard.challenges.refuse_form", compact("challengeId", "teamId"));
	}

	public function validationList() {
		$validations_pending = ChallengeValidation::where("validated", "=", 0)->orderBy("submittedOn", 'last_update', "dsc")->get();
		$validations_treated = ChallengeValidation::where("validated", "=", -1)->orWhere("validated", "=", 1)->orderBy("last_update", "dsc")->get();
		return view("dashboard.challenges.submissions", compact("validations_pending", "validations_treated"));
	}

	/**
	 * used when the team leader send a validation proof
	 * in order to validate a challenge
	 */
	public function submitToValidation(Request $request, int $challengeId, int $teamId) {

		$this->validate($request, [
			"proof" => "required"
		]);

		$file = fopen($request->file('proof')->getRealPath(), "r+");
		$filename = uniqid().".".$request->file("proof")->guessExtension();
		Storage::disk('validation-proofs')->put($filename, $file);
		fclose($file);



			$team = Team::find($teamId);
		if($team->hasAlreadyMadeSubmission($challengeId)){

			Storage::disk("validation-proofs")->delete($team->challenges()->first()->pivot->pic_url);
			$team->challenges()->updateExistingPivot($challengeId, ["pic_url" => $filename, "validated" => 0]);
		}else{
			$challenge = Challenge::find($challengeId);
			$team->challenges()->save($challenge, ["submittedOn"=> new \DateTime("now"), "pic_url" => $filename]);
		}


		$request->flash("success", "La défis a bien été soumis à validation");
		return redirect(route("challenges.list"));
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
	public function submitChallengeForm(int $idChallenge) {
		$challenge = DB::table('challenges')->where("id", "=", $idChallenge)->first();
		return View::make("dashboard.challenges.submit", [
			"challenge" => $challenge
		]);
	}

	public function deleteChallenge(int $idChallenge) {
		DB::table("challenges")->where("id", "=", $idChallenge)->delete();
		return redirect(route("challenges.list"));
	}

	public function showChallengesList() {
		$challenges = DB::table("challenges")->get();
		$team = Team::find(Auth()->user()->team_id);
		return View::make('dashboard.challenges.list', compact("challenges", "team"));
	}

	public function showSentChallenges() {
		$team_id = Auth::user()->team_id;
		$validations = ChallengeValidation::where("team_id", "=", $team_id)->get();
		$score = Team::find($team_id)->challenges()->wherePivot("validated", 1)->sum("points");
		return view("dashboard.challenges.challenges_sent", compact("validations", "score"));
	}

	private function setChallengeStatus(int $challengeId, int$teamId, int $validate=1)
	{
		$challenge = Team::find($teamId)->challenges()->where("id", "=", $challengeId)->first();
		$challenge->teams()->updateExistingPivot($teamId, ["validated" => $validate, "last_update" => new \DateTime("now"), "update_author" => Auth::user()->id]);
		$challenge->save();
		return redirect(route("challenges.validationsList"));
	}

	public function resetStatus(int $challengeId, int $teamId) 
	{
		return $this->setChallengeStatus($challengeId, $teamId, 0);
	}


	public function accept(int $challengeId, int $teamId) {
		return $this->setChallengeStatus($challengeId, $teamId);
	}

	public function refuse(int $challengeId, int $teamId)
	{
		return $this->setChallengeStatus($challengeId, $teamId, -1);
	}
}
