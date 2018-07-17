<?php

namespace App\Http\Controllers\All;

use App\Http\Controllers\Controller;
use App\Models\User;
use App;
use Request;
use View;
use Redirect;
use Session;
use Config;
use Response;
use Auth;
use DB;
use EtuUTT;

/**
 * OAuth authentication with the etu.utt.fr website.
 *
 * @author  Thomas Chauchefoin <thomas@chauchefoin.fr>
 * @license MIT
 */
class OAuthController extends Controller
{
    /**
     * Redirect the user to the OAuth modal.
     *
     * @return RedirectResponse
     */
    public function auth()
    {
        $id = Config::get('services.etuutt.client.id');
        return Redirect::to(Config::get('services.etuutt.baseuri.public').'/api/oauth/authorize?client_id=' . $id . '&scopes=private_user_account&response_type=code&state=xyz');
    }

    /**
     * Handle the authorization_code to request an access_token.
     *
     * @return Response
     */
    public function callback()
    {
        // The user should not have been redirected here without the
        // "authorization_code" variable in the url. If it's not the
        // case, do not go further.
        if (! Request::has('authorization_code')) {
            App::abort(401);
        }

        $client = new \GuzzleHttp\Client([
            'base_uri' => Config::get('services.etuutt.baseuri.api'),
            'auth' => [
                Config::get('services.etuutt.client.id'),
                Config::get('services.etuutt.client.secret')
            ]
        ]);

        $params = [
            'grant_type'         => 'authorization_code',
            'authorization_code' => Request::input('authorization_code')
        ];

        try {
            $response = $client->post('/api/oauth/token', ['form_params' => $params]);
        } catch (\GuzzleHttp\Exception\GuzzleException $e) {
            // An error 400 from the server is usual when the authorization_code
            // has expired. Redirect the user to the OAuth gateway to be sure
            // to regenerate a new authorization_code for him :-)
            if ($e->hasResponse() && $e->getResponse()->getStatusCode() === 400) {
                return $this->auth();
            }
            App::abort(500);
        }

        $json = json_decode($response->getBody()->getContents(), true);
        $access_token = $json['access_token'];
        $refresh_token = $json['refresh_token'];

        try {
            $response = $client->get('/api/private/user/account?access_token=' . $json['access_token']);
        } catch (\GuzzleHttp\Exception\GuzzleException $e) {
            App::abort(500);
        }

        $json = json_decode($response->getBody()->getContents(), true)['data'];

        $user = EtuUTT::updateOrCreateUser($json, $access_token, $refresh_token);

        // Remember the user accross the whole website.
        Auth::login($user, true);

        return Redirect::route('menu');
    }

    /**
     * Disconnect the user by resetting his session and redirecting him to etu.utt.fr.
     *
     * @return Response
     */
    public function logout()
    {
        Session::flush();
        Auth::logout();
        // We have to redirect the user to a web page wich will be disconnecting
        // him from the etu.utt.fr website. Then he'll be redirected to the
        // application index. That's dirty but I don't see any alternative.
        return View::make('redirection');
    }
}
