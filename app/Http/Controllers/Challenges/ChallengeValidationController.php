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
     * used when a member of the team sends a validation proof
     * in order to validate a challenge
     * same function is used to update: allow a team to
     * change the pic for the validation
     */
    public function create(Request $request, int $teamId, int $challengeId) {

        $challenge = Challenge::find($challengeId);

        //If the deadline has passed, redirect with error
        if($challenge->deadlineHasPassed()) {
            return redirect(route('challenges.list'))->with('error', 'La deadline est dépassée.');
        }

        $this->validate($request, [
            'proof' => 'required'
        ]);

        $file = fopen($request->file('proof')->getRealPath(), 'r+');
        $filename = uniqid().'.'.$request->file('proof')->guessExtension();
        Storage::disk('validation-proofs')->put($filename, $file);
        fclose($file);

        $team = Team::find($teamId);
        $challenge = Challenge::find($challengeId);
        $user = Auth::user();
        $user->challenges()->save($challenge,['submittedOn' => new \DateTime('now'), 'pic_url' => $filename, 'team_id' => $user->team_id]);
        $request->flash('success', 'La défis a bien été soumis à validation');
        return redirect(route('challenges.list'));
    }

    public function list() {
        $validations_pending = ChallengeValidation::where('validated', '=', 0)->orderBy('submittedOn', 'last_update', 'desc')->get();
        $validations_treated = ChallengeValidation::where('validated', '=', -1)->orWhere('validated', '=', 1)->orderBy('last_update', 'desc')->get();
        return view('dashboard.challenges.submissions', compact('validations_pending', 'validations_treated'));
    }

    /**
     * Display the form for the admin to refuse
     * a challenge
     */
    public function refuseForm(int $validationId) {
        return view('dashboard.challenges.refuse_form', compact('validationId'));
    }

    private function setChallengeStatus(int $validationId, int $adjustment=0, int $validate=1, string $message=null)
    {
        $validation = ChallengeValidation::find($validationId);
        //$challenge->teams()->updateExistingPivot($teamId, );
        $validation->fill(['validated' => $validate, 'last_update' => new \DateTime('now'), 'update_author' => Auth::user()->id, 'message' => $message, 'adjustment' => $adjustment]);
        $validation->save();
        return redirect(route('validation.list'));
    }

    public function resetStatus(int $validationId) 
    {
        return $this->setChallengeStatus($validationId, 0, 0);
    }


    public function accept(Request $request, int $validationId) {
        $this->validate($request, [
            "adjustment" => "integer|"
        ]);
        return $this->setChallengeStatus($validationId, $request->adjustment);
    }

    public function refuse(Request $request, int $validationId)
    {
        $this->validate($request, [
            'message' => 'required|max:140',
        ]);
        $message = $request->message;
        return $this->setChallengeStatus($validationId, -1, $message);
    }
}
