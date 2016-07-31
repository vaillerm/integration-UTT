<?php

namespace App\Http\Controllers;

use App\Models\Newcomer;
use EtuUTT;
use Request;
use Config;
use View;
use Crypt;
use Redirect;
use Auth;

class AuthController extends Controller
{
    /**
     * Show the authentication for newcomer page
     *
     * @return Response
     */
    public function login()
    {
        return View::make('newcomer.login');
    }

    /**
     * Authenticate the newcomer
     *
     * @return Response
     */
    public function loginSubmit()
    {
        $newcomer = Newcomer::where('login', Request::get('login'))->get()->first();
        if ($newcomer) {
            $password = Crypt::decrypt($newcomer->password);
            if ($password == Request::get('password')) {
                Auth::login($newcomer, true);
                return Redirect::route('newcomer.home')->withSuccess('Vous êtes maintenant connecté.');
            }
        }
        return $this->error('Identifiant ou mot de passe incorrect. Contactez integration@utt.fr si vous n\'arrivez pas à vous connecter.');
    }

    /**
     * log out the newcomer
     *
     * @return Response
     */
    public function logout()
    {
        Auth::logout();
        return Redirect::route('index')->withSuccess('Vous êtes maintenant déconnecté.');
    }
}
