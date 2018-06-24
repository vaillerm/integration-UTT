<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Newcomer;
use App\Classes\NewcomerMatching;
use Request;
use View;
use Session;
use Redirect;
use EtuUTT;
use Auth;

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
        $referrals = User::student()->where('referral', true)->orderBy('created_at', 'asc')->get();
        return View::make('dashboard.referrals.list', [
            'referrals' => $referrals,
        ]);
    }

    public function matchToNewcomers()
    {
        if (NewcomerMatching::matchReferrals()) {
            return redirect(route('dashboard.newcomers.list'))->withSuccess('Tous les nouveaux ont maintenant un parrain !');
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
        $input = Request::only('referralCountries', 'newcomerCountries', 'referralBranches', 'newcomerBranches');
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
        return redirect(route('dashboard.referrals.match'));
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
            'referrals' => User::where('referral', 1)->where('referral_validated', 1)->where('branch', '<>', 'TC')->orderBy('last_name')->with('newcomers')->get(),
        ]);
    }
}
