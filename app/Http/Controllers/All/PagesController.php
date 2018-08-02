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
    }
}
