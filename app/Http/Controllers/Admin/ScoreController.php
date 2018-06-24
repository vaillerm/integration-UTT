<?php

namespace App\Http\Controllers\Admin;

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
     * List all the factions and their teams.
     *
     * @return Response
     */
    public function getChampionship()
    {
        return View::make('dashboard.championship.admin', [
            'factions' => Faction::all()
        ]);
    }

    /**
     * Update the new points for all the teams.
     *
     * @return RedirectResponse
     */
    public function postChampionship()
    {
        foreach (Team::whereNotNull('faction_id')->get() as $team) {
            $input = Request::input('team-'.$team->id);
            if ($input !== null) {
                $team->points = $input;
                $team->save();
            }
        }
        return $this->success('Les points ont été mis à jour !');
    }
}
