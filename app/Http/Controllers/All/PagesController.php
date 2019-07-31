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
     * App page to download app
     *
     * @return Response
     */
    public function getApppage()
    {
        return View::make('apppage');
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

    /**
     * @return Response
     */
    public function getTrombi()
    {
        $users = User::where('mission', '!=', '')
            ->orderBy('mission_order', 'desc')
            ->orderBy('mission', 'asc')
            ->orderBy('mission_respo', 'desc')
            ->orderBy('last_name', 'asc')->get();

        return View::make('All.trombi', [
            'users' => $users,
        ]);
    }

    /**
     * Show an image containg the phone to avoid bot crawling on trombi
     * @return Response
     */
    public function getTrombiPhome($id)
    {
        $user = User::where('mission', '!=', '')->findOrFail($id);

        // Try to uniformize phone format
        $phone = $user->phone;
        if (preg_match('/^(?:0([0-9])|(?:00|\+)33[\. -]?([0-9])|([0-9]))[\. -]?([0-9]{2})[\. -]?([0-9]{2})[\. -]?([0-9]{2})[\. -]?([0-9]{2})[\. -]?$/', $phone, $m)) {
            $phone = '0'.$m[1].$m[2].$m[3].'.'.$m[4].'.'.$m[5].'.'.$m[6].'.'.$m[7];
        }
        else if(preg_match('/^(?:00|\+)(.+)$/', $phone, $m)) {
            $phone = '+'.$m[1];
        }

        if (empty($phone))
        {
            return response();
        }
        $path = storage_path() . '/trombi-phones/' . $phone . '.png';

        if (!file_exists(storage_path() . '/trombi-phones/' . $phone . '.png')) {
            $string = $phone;

            $font  = 2;
            $width  = imagefontwidth($font) * strlen($string);
            $height = imagefontheight($font);

            $image = imagecreatetruecolor($width, $height);
            $white = imagecolorallocate($image, 255, 255, 255);
            $black = imagecolorallocate($image, 0, 0, 0);
            imagefill($image, 0, 0, $white);
            imagestring($image, $font, 0, 0, $string, $black);

            if (!file_exists(storage_path() . '/trombi-phones/')) {
                mkdir(storage_path() . '/trombi-phones/', 0755, true);
            }
            imagepng($image, storage_path() . '/trombi-phones/' . $phone . '.png');
            imagedestroy($image);

        }

        return response()->file(storage_path() . '/trombi-phones/' . $phone . '.png');
    }
}
