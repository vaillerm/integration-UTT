<?php

namespace App\Http\Controllers\Students;

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
class PagesController extends Controller
{

    /**
     * Display dashboard index, with changelog, etc.
     *
     * @return Response
     */
    public function getDashboardHome()
    {
        return View::make('dashboard.home');
    }

    /**
     * Sort of menu for the user.
     *
     * @return Response
     */
    public function getMenu()
    {
        $teams = Team::all();
        $countTC = 0;
        $countBranch = 0;
        foreach ($teams as $team) {
            if($team->isTC()){
                $countTC++;
            }
            else {
                $countBranch++;
            }
        }

        //info("Nombre de team de TC : " . $countTC . " Nombre de team de Branche : " . $countBranch);
        return View::make('menu')
            ->with([
                'student' => Auth::user(),
                'teamLeftTC' => Config::get('services.ce.maxTeamTc') - $countTC,
                'teamLeftBranch' => Config::get('services.ce.maxTeamBranch') - $countBranch,
            ]);
    }
}
