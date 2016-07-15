<?php

namespace App\Http\Controllers;

use Request;
use Redirect;
use DB;
use View;

/**
 * Handle dashboard pages and administrators actions unrealted to any other controller.
 *
 * @author  Thomas Chauchefoin <thomas@chauchefoin.fr>
 * @license MIT
 */
class DashboardController extends Controller
{

    /**
     * Display dashboard index, with changelog, etc.
     *
     * @return Response
     */
    public function getIndex()
    {
        return View::make('dashboard.home');
    }
}
