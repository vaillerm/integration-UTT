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
    public function addForm() {
        return View::make('dashboard.challenges.add');
    }

    public function ModifyChallengeForm(int $challengeId) {
        $challenge = Challenge::find($challengeId);
        return view('dashboard.challenges.modify', compact('challenge'));
    }

    /**
     * Modify a challenge
     */
    public function modify(ChallengeRequest $request, int $challengeId) {
        $challenge_to_update = Challenge::find($challengeId);
        $challenge_to_update->update($request->toArray());
        return redirect(route('challenges.list'));
    }



    /**
     * Add a challenge
     */
    public function add(ChallengeRequest $request) {
        $challenge = $request->toArray();
        Challenge::create($challenge);
        return redirect(route('challenges.list'))->withSuccess('Défis ajouté');
    }

    /**
     * Used by team leader to submit a challenge to be validated
     */
    public function submitChallengeForm(int $idChallenge) {
        $challenge = DB::table('challenges')->where('id', '=', $idChallenge)->first();
        return View::make('dashboard.challenges.submit', [
            'challenge' => $challenge
        ]);
    }

    public function delete(int $idChallenge) {
        DB::table('challenges')->where('id', '=', $idChallenge)->delete();
        return redirect(route('challenges.list'));
    }

    public function list() {
        $challenges = Challenge::all();
        $team = Team::find(Auth()->user()->team_id);
        return View::make('dashboard.challenges.list', compact('challenges', 'team'));
    }

}
