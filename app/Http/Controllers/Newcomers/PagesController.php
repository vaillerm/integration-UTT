<?php

namespace App\Http\Controllers\Newcomers;

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
     * Newcomer's home page.
     *
     * @return Response
     */
    public function getNewcomersHomepage()
    {
        return view('Newcomers.Pages.home');
    }

    /**
     * Newcomer's done page.
     *
     * @return Response
     */
    public function getNewcomersDone()
    {
        return View::make('Newcomers.Pages.done');
    }

    /**
     * @return Response
     */
    public function getFAQ()
    {
        return View::make('Newcomers.Pages.faq');
    }

    /**
     * @return Response
     */
    public function getDeals()
    {
        return View::make('Newcomers.Pages.deals');
    }
}
