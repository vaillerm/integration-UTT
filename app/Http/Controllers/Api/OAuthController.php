<?php

namespace App\Http\Controllers\Api;

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
     * Return a JSON object with the link to auth with Etu UTT
     *
     * @return Response
     */
    public function getRedirectLink()
    {
        $id = Config::get('services.etuutt.mobile_client.id');
        return Response::json([
            'redirectUri' => Config::get('services.etuutt.baseuri.public').'/api/oauth/authorize?client_id=' . $id . '&scopes=private_user_account&response_type=code&state=xyz'
        ]);
    }


    /**
     * Handle the authorization_code.
     *
     * @return Response
     */
    public function mobileCallback()
    {
        // authorization code required to continue
        if (! Request::has('authorization_code')) {
            return Response::json(["message" => "missing parameter : authorization_code"], 401);
        }

        $client = new \GuzzleHttp\Client([
            'base_uri' => Config::get('services.etuutt.baseuri.api'),
            'auth' => [
                Config::get('services.etuutt.mobile_client.id'),
                Config::get('services.etuutt.mobile_client.secret')
            ]
        ]);

        $params = [
            'grant_type'         => 'authorization_code',
            'authorization_code' => Request::input('authorization_code')
        ];

        try {
            $response = $client->post('/api/oauth/token', ['form_params' => $params]);
        } catch (\GuzzleHttp\Exception\GuzzleException $e) {
            return Response::json(["message" => "authentication failed"], 500);
        }

        $json = json_decode($response->getBody()->getContents(), true);
        $access_token = $json['access_token'];
        $refresh_token = $json['refresh_token'];

        try {
            $response = $client->get('/api/private/user/account?access_token=' . $json['access_token']);
        } catch (\GuzzleHttp\Exception\GuzzleException $e) {
            return Response::json(["message" => "failed to fetch your account data"], 500);
        }

        $json = json_decode($response->getBody()->getContents(), true)['data'];

        $user = EtuUTT::updateOrCreateUser($json, $access_token, $refresh_token);

        // generate auth token for this student
        $createdToken = $user->createToken("etu utt");
        $passport_access_token = $createdToken->accessToken;
        $passport_expires_at = $createdToken->token->expires_at;

        return Response::json([
            "access_token" => $passport_access_token,
            "expires_at" => $passport_expires_at,
        ]);
    }

    /**
     * if the request passed the auth:api middleware, it means that the token is still valid.
     * So return code 200.
     *
     * @return Response
     */
    public function checkApiToken() {
        return Response::json(["message" => "valid token"]);
    }

    /**
     * Revoke and delete the passport token of the authenticated student
     *
     * @return Response
     */
    public function revokeApiToken()
    {
        $token = Auth::guard('api')->user()->token();
        $token->revoke();
        $token->delete();

        return Response::json(["success" => true]);
    }
}
