<?php

namespace App\Http\Controllers\Challenges;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Storage;
use App\Models\Team;
use App\Models\Challenge;
use App\Models\ChallengeValidation;
use Auth;

class ChallengeValidationController extends Controller
{
	/**
	 * used when the team leader send a validation proof
	 * in order to validate a challenge
	 * same function is used to update: allow a team to
	 * change the pic for the validation
	 */
	public function createOrUpdate(Request $request, int $teamId, int $challengeId) {

		$challenge = Challenge::find($challengeId);

		if($challenge->deadlineHasPassed()) {
			return redirect(route("challenges.list"))->with("error", "La deadline est dépassée.");
		}

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
			$team->challenges()->updateExistingPivot($challengeId, ["pic_url" => $filename, "validated" => 0, "message" => null]);
		}else{
			$challenge = Challenge::find($challengeId);
			$team->challenges()->save($challenge, ["submittedOn"=> new \DateTime("now"), "pic_url" => $filename]);
		}
		$request->flash("success", "La défis a bien été soumis à validation");
		return redirect(route("challenges.list"));
	}

	public function list() {
		$validations_pending = ChallengeValidation::where("validated", "=", 0)->orderBy("submittedOn", 'last_update', "dsc")->get();
		$validations_treated = ChallengeValidation::where("validated", "=", -1)->orWhere("validated", "=", 1)->orderBy("last_update", "dsc")->get();
		return view("dashboard.challenges.submissions", compact("validations_pending", "validations_treated"));
	}

	/**
	 * Display the form for the admin to refuse
	 * a challenge
	 */
	public function refuseForm(int $challengeId, int $teamId) {
		return view("dashboard.challenges.refuse_form", compact("challengeId", "teamId"));
	}

	private function setChallengeStatus(int $challengeId, int$teamId, int $validate=1, string $message=null)
	{
		$challenge = Team::find($teamId)->challenges()->where("id", "=", $challengeId)->first();
		$challenge->teams()->updateExistingPivot($teamId, ["validated" => $validate, "last_update" => new \DateTime("now"), "update_author" => Auth::user()->id, "message" => $message]);
		$challenge->save();
		return redirect(route("validation.list"));
	}

	public function resetStatus(int $challengeId, int $teamId) 
	{
		return $this->setChallengeStatus($challengeId, $teamId, 0);
	}


	public function accept(int $challengeId, int $teamId) {
		return $this->setChallengeStatus($challengeId, $teamId);
	}

	public function refuse(Request $request, int $challengeId, int $teamId)
	{
		$this->validate($request, [
			'message' => 'required|max:140',
		]);
		$message = $request->message;
		return $this->setChallengeStatus($challengeId, $teamId, -1, $message);
	}
}
