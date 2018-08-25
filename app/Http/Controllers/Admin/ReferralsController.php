<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Newcomer;
use App\Classes\NewcomerMatching;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Redirect;
use EtuUTT;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

/**
 * @author  Thomas Chauchefoin <thomas@chauchefoin.fr>
 * @license MIT
 */
class ReferralsController extends Controller
{

    /**
     * Show the validation form for referral's free text.
     *
     * @return Response
     */
    public function getValidation()
    {
        $date = new \Datetime();
        $referral = User::where('referral', true)->where('referral_validated', 0)->where('email', '!=', '')
                            ->where('phone', '!=', '')->where('referral_text', '!=', '')
                            ->orderByRaw('RAND()')->first();
        return View::make('dashboard.referrals.validation')->with('referral', $referral);
    }

    /**
     * Handle text validation.
     *
     * @return RedirectResponse
     */
    public function postValidation()
    {
        $id = Request::input('user_id');
        $referral = User::where('id',$id)->first();
        if(!$referral)
            abort(404);

        if ($referral->referral_validated) {
            return Redirect::back()->withError('Quelqu\'un a déjà validé cette personne :-(');
        }
        $referral->referral_validated = 1;
        $referral->referral_text = Request::input('referral_text');
        $referral->save();

        return Redirect::back()->withSuccess('Texte validé pour ' . $referral->first_name . ' ' . $referral->last_name . ' !');
    }

    /**
     * List all the referrals.
     *
     * @return Response
     */
    public function index()
    {
        $referrals = User::student()->with(['newcomers'])->where('referral', true)->orderBy('created_at', 'asc')->get();
        $newcomersCounts = DB::table('users')
            ->select(DB::raw('count(*) as `count`, branch'))
            ->groupBy('branch')
            ->where('is_newcomer', true)
            ->get();
        $newcomersWithoutRefCounts = DB::table('users')
            ->select(DB::raw('count(*) as `count`, branch'))
            ->groupBy('branch')
            ->where('is_newcomer', true)
            ->whereNull('referral_id')
            ->get();

        return View::make('dashboard.referrals.list', [
            'referrals' => $referrals,
            'newcomersCounts' => $newcomersCounts,
            'newcomersWithoutRefCounts' => $newcomersWithoutRefCounts,
        ]);
    }

    public function matchToNewcomers($force = false)
    {
        if (NewcomerMatching::matchReferrals($force == 'force')) {
            return redirect(route('dashboard.newcomers.list'))->withSuccess('Les nouveaux ont maintenant un parrain !');
        }
        return redirect(route('dashboard.newcomers.list'))->withError('Il n\'y a pas assez de parrains pour cette algorithme. Veuillez le changer.');
    }

    public function prematch()
    {
        return View::make('dashboard.referrals.prematch', [
            'referralCountries' => User::select('country')->where(['referral' => 1, 'referral_validated' => 1])->groupBy('country')->pluck('country'),
            'newcomerCountries' => User::newcomer()->select('country')->groupBy('country')->pluck('country'),
            'referralBranches' => User::select('branch')->where(['referral' => 1, 'referral_validated' => 1])->groupBy('branch')->pluck('branch'),
            'newcomerBranches' => User::newcomer()->select('branch')->groupBy('branch')->pluck('branch'),
        ]);
    }

    public function prematchSubmit()
    {
        $input = Request::only('referralCountries', 'newcomerCountries', 'referralBranches', 'newcomerBranches', 'force');
        // Referral Countries
        foreach ($input['referralCountries'] as $key => $value) {
            if ($key === 0) {
                $key = '';
            }
            User::where('country', $key)->update(['country' => $value]);
        }
        // UpdateNewcomersTable Countries
        foreach ($input['newcomerCountries'] as $key => $value) {
            if ($key === 0) {
                $key = '';
            }
            User::newcomer()->where('country', $key)->update(['country' => $value]);
        }
        // Referral branches
        foreach ($input['referralBranches'] as $key => $value) {
            if ($key === 0) {
                $key = '';
            }
            User::where('branch', $key)->update(['branch' => $value]);
        }
        // UpdateNewcomersTable branches
        foreach ($input['newcomerBranches'] as $key => $value) {
            if ($key === 0) {
                $key = '';
            }
            User::newcomer()->where('branch', $key)->update(['branch' => $value]);
        }

        // Redirect to referral assignation
        return redirect(route('dashboard.referrals.match', [
            'force' => empty($input['force']) ? '' : 'force',
        ]));
    }


    public function signsTC()
    {
        return View::make('dashboard.referrals.signs', [
            'referrals' => User::where('referral', 1)->where('referral_validated', 1)->with('newcomers')->where('branch', 'TC')->orderBy('last_name')->get(),
        ]);
    }

    public function slidesTC()
    {
        return View::make('dashboard.referrals.slides', [
            'referrals' => User::where('referral', 1)->where('referral_validated', 1)->where('branch', 'TC')->with('newcomers')->orderBy('last_name')->with('newcomers')->get(),
        ]);
    }


    public function signsBranch()
    {
        return View::make('dashboard.referrals.signs', [
            'referrals' => User::where('referral', 1)->where('referral_validated', 1)->where('branch', '<>', 'TC')->orderBy('last_name')->with('newcomers')->get(),
        ]);
    }

    public function slidesBranch()
    {
        return View::make('dashboard.referrals.slides', [
            'referrals' => User::where('referral', 1)->where('referral_validated', 1)->where('branch', '<>', 'TC')
            ->where('branch', '<>', 'A2I')
            ->where('branch', '<>', 'PAIP')
            ->where('branch', '<>', 'RE')
            ->where('branch', '<>', 'ISC')
            ->orderBy('last_name')->with('newcomers')->get(),
        ]);
    }
}
