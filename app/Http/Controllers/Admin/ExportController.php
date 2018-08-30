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
 * Export actions
 */
class ExportController extends Controller
{
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
     * Export the referrals and the related newcomers into a CSV file.
     *
     * @return string
     */
    public function getExportReferrals()
    {
        $referrals = User::select([\DB::raw('users.first_name'), \DB::raw('users.last_name')])
        ->orderBy('last_name')
        ->rightjoin('users as n', 'users.id', '=', 'n.referral_id')
        ->addSelect([\DB::raw('n.branch as branch'), \DB::raw('n.first_name as newcomer_first_name'), \DB::raw('n.last_name as newcomer_last_name'), \DB::raw('n.phone as newcomer_phone')])
        ->get();
        return Excel::create('Parrains', function ($file) use ($referrals) {
            $file->sheet('', function ($sheet) use ($referrals) {
                $sheet->fromArray($referrals);
            });
        })->export('xls');
    }

    /**
     * Export the newcomers.
     *
     * @return string
     */
    public function getExportNewcomers()
    {
        $newcomers = User::select([\DB::raw('users.first_name'), \DB::raw('users.last_name'), \DB::raw('users.branch')])
        ->where('users.is_newcomer', true)
        ->orderBy('last_name')
        ->leftjoin('users as s', 's.id', '=', 'users.referral_id')
        ->addSelect([\DB::raw('s.first_name as referral_first_name'), \DB::raw('s.last_name as referral_last_name'), \DB::raw('s.email as referral_email'), \DB::raw('s.phone as referral_phone')])
        ->get();
        return Excel::create('Nouveaux', function ($file) use ($newcomers) {
            $file->sheet('', function ($sheet) use ($newcomers) {
                $sheet->fromArray($newcomers);
            });
        })->export('xls');
    }

    /**
     * Export the teams and CE into a XLS file.
     *
     * @return string
     */
    public function getExportTeams()
    {
        $users = User::select(['first_name', 'last_name', 'phone', 'email', 'team_id'])
        ->orderBy('last_name')
        ->where('ce', 1)
        ->whereNotNull('team_id')
        ->where('team_accepted', 1)
        ->join('teams as t', 't.id', '=', 'users.team_id')
        ->join('factions as f', 'f.id', '=', 't.faction_id')
        ->addSelect('t.faction_id', \DB::raw('t.name as team_name'), \DB::raw('f.name as faction_name'))
        ->get();
        return Excel::create('Equipe', function ($file) use ($users) {
            $file->sheet('', function ($sheet) use ($users) {
                $sheet->fromArray($users);
            });
        })->export('xls');
    }

    /**
     * Export all the users wither their contact informations
     *
     * @return string
     */
    public function getExportStudents()
    {
        $users = User::select(['first_name', 'last_name', 'student_id', 'phone', 'email',
        \DB::raw('is_newcomer as nouveau'), \DB::raw('referral_validated as parrain'), \DB::raw('IF(team_id > 0,1,0) as ce'), \DB::raw('volunteer as benevole'), 'orga', 'secu'])
        ->orderBy('last_name')
        ->get();
        return Excel::create('Etudiants', function ($file) use ($users) {
            $file->sheet('', function ($sheet) use ($users) {
                $sheet->fromArray($users);
            });
        })->export('xls');
    }
}
