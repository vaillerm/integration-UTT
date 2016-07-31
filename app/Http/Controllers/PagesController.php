<?php

namespace App\Http\Controllers;

use App\Models\Student;
use App\Models\Team;
use App\Models\Faction;
use App\Models\Newcomer;
use View;
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
     * Temporary hompage.
     *
     * @return Response
     */
    public function getHomepage()
    {
        return View::make('homepage');
    }

    /**
     * Sort of menu for the user.
     *
     * @return Response
     */
    public function getMenu()
    {
        return View::make('menu')
            ->with([
                'student' => EtuUTT::student(),
                'teamLeft' => Config::get('services.ce.maxteam') - Team::count(),
            ]);
    }

    /**
     * Export pages.
     *
     * @return Response
     */
    public function getExports()
    {
        return View::make('dashboard.exports');
    }

    /**
     * Newcomer's home page.
     *
     * @return Response
     */
    public function getNewcomersHomepage()
    {
        return View::make('newcomer.home');
    }

    /**
     * Newcomer's done page.
     *
     * @return Response
     */
    public function getNewcomersDone()
    {
        return View::make('newcomer.done');
    }

    /**
     * Export the referrals and the related newcomers into a CSV file.
     *
     * @return string
     */
    public function getExportReferrals()
    {
        $referrals = Referral::orderBy('last_name')->where('validated', 1)->get();
        // Embed the referral's newcomers in the document.
        foreach ($referrals as &$referral) {
            for ($i=0; $i < $referral->newcomers()->count(); $i++) {
                $newcomer = $referral->newcomers()->get()->toArray()[$i];
                $referral['Fillot '.$i] = $newcomer['first_name'].' '.$newcomer['last_name'];
            }
        }
        return Excel::create('Parrains', function ($file) use ($referrals) {
            $file->sheet('', function ($sheet) use ($referrals) {
                $sheet->fromArray($referrals);
            });
        })->export('csv');
    }

    /**
     * Export the newcomers.
     *
     * @return string
     */
    public function getExportNewcomers()
    {
        $newcomers = Newcomer::whereNotNull('referral_id')->orderBy('last_name')->get();
        $data = [['Fillot', 'Parrain', 'Email parrain', 'Téléphone parrain']];
        foreach ($newcomers as $newcomer) {
            $data[] = [$newcomer->last_name.' '.$newcomer->first_name, $newcomer->email,
                       $newcomer->referral->last_name.' '.$newcomer->referral->first_name,
                       $newcomer->referral->email,
                       $newcomer->referral->phone
            ];
        }

        return Excel::create('Fillots', function ($file) use ($data) {
            $file->sheet('', function ($sheet) use ($data) {
                $sheet->fromArray($data);
            });
        })->export('csv');
    }

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

    /**
     * @return Response
     */
    public function getScores()
    {
        return View::make('scores')->with(['factions' => Faction::all()]);
    }
}
