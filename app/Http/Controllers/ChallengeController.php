<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use EtuUTT;
use View;

class ChallengeController extends Controller
{
	/**
	 * Allow an admin to add a challenge
	 * @Return null
	 */
	public function addChallenge() {
		if(!EtuUTT::student()->isAdmin()) {
			return $this->error('Vous n\'avez pas le droit d\'accéder à cette page.');
		}
		return View::make('dashboard.challenges.add');
	}
}
