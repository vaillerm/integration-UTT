<?php

namespace App\Http\Controllers;

use App\Models\Student;
use App\Models\Team;
use App\Models\Faction;
use App\Models\Newcomer;
use App;
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
        $referrals = Student::select([\DB::raw('students.first_name'), \DB::raw('students.last_name')])
        ->orderBy('last_name')
        ->rightjoin('newcomers as n', 'students.student_id', '=', 'n.referral_id')
        ->addSelect([\DB::raw('n.branch as branch'), \DB::raw('n.first_name as newcomer_first_name'), \DB::raw('n.last_name as newcomer_last_name'), \DB::raw('n.phone as newcomer_phone')])
        ->get();
        return Excel::create('Referrals', function ($file) use ($referrals) {
            $file->sheet('', function ($sheet) use ($referrals) {
                $sheet->fromArray($referrals);
            });
        })->export('csv');


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
        $newcomers = Student::newcomer()->select([\DB::raw('newcomers.first_name'), \DB::raw('newcomers.last_name'), \DB::raw('newcomers.branch')])
        ->orderBy('last_name')
        ->leftjoin('students as s', 's.student_id', '=', 'newcomers.referral_id')
        ->addSelect([\DB::raw('s.first_name as referral_first_name'), \DB::raw('s.last_name as referral_last_name'), \DB::raw('s.email as referral_email'), \DB::raw('s.phone as referral_phone')])
        ->get();
        return Excel::create('Newcomers', function ($file) use ($newcomers) {
            $file->sheet('', function ($sheet) use ($newcomers) {
                $sheet->fromArray($newcomers);
            });
        })->export('csv');
    }

    /**
     * Export the teams and CE into a CSV file.
     *
     * @return string
     */
    public function getExportTeams()
    {
        $students = Student::select(['first_name', 'last_name', 'phone', 'email', 'team_id'])
        ->orderBy('last_name')
        ->where('ce', 1)
        ->whereNotNull('team_id')
        ->where('team_accepted', 1)
        ->join('teams as t', 't.id', '=', 'students.team_id')
        ->addSelect(\DB::raw('t.name as team_name'))
        ->get();
        return Excel::create('Teams', function ($file) use ($students) {
            $file->sheet('', function ($sheet) use ($students) {
                $sheet->fromArray($students);
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

    /**
     * @return Response
     */
    public function getFAQ()
    {
        return View::make('newcomer.faq');
    }

    /**
     * @return Response
     */
    public function getDeals()
    {
        return View::make('newcomer.deals');
    }

    /**
     * @return Response
     */
    public function getQrCode($id)
    {
        if (!$id || !filter_var($id, FILTER_VALIDATE_INT)
            && $id > 0 && $id < 100000) {
            return App::abort(401);
        }

        if (!file_exists(storage_path() . '/qrcodes/' . $id . '.png')) {
            \PHPQRCode\QRcode::png($id, storage_path() . '/qrcodes/' . $id . '.png', 2, 25, 2);
        }

        return response()->file(storage_path() . '/qrcodes/' . $id . '.png');


        // return View::make('newcomer.deals');
    }
}
