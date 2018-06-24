<?php

namespace App\Http\Controllers\All;

use App\Http\Controllers\Controller;
use App\Models\User;
use EtuUTT;
use Request;
use Config;
use View;
use Crypt;
use Redirect;
use Auth;
use Response;

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
        $user = User::where('login', Request::get('login'))->get()->first();
        if ($user && !empty($user->password)) {
            $password = Crypt::decrypt($user->password);
            if ($password == Request::get('password')) {
                Auth::login($user, true);
                if ($user->isNewcomer()) {
                    return Redirect::route('newcomer.home')->withSuccess('Vous êtes maintenant connecté.');
                }
                else {
                    return Redirect::route('menu')->withSuccess('Vous êtes maintenant connecté.');
                }
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
