<?php

namespace App\Http\Controllers\All;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Team;
use App\Models\Faction;
use App\Models\Newcomer;
use App;
use View;
use Auth;
use Excel;
use Request;
use Session;
use EtuUTT;
use Config;

/**
 * Handle misc. pages.
 *
 * @author  Thomas Chauchefoin <thomas@chauchefoin.fr>
 * @license MIT
 */
class ScoreController extends Controller
{
    /**
     * @return Response
     */
    public function getScores()
    {
        return View::make('scores')->with(['factions' => Faction::all()]);
    }
}
