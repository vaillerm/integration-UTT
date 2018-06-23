<?php

namespace App\Http\Controllers;

use App\Models\Student;
use App;
use Request;
use View;
use Redirect;
use Session;
use Config;
use Response;
use Auth;
use DB;

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

    private function updateUser($json, $access_token, $refresh_token)
    {
        // If the user is new, import some values from the API response.
        $student = Student::where('etuutt_login', $json['login'])->first();
        if ($student === null) {
            $student = new Student([
                'etuutt_login'  => $json['login'],
                'student_id'    => $json['studentId'],
                'first_name'    => $json['firstName'],
                'last_name'     => $json['lastName'],
                'surname'       => $json['surname'],
                'email'         => $json['email'],
                'facebook'      => $json['facebook'],
                'phone'         => ($json['phonePrivacy'] == 'public') ? $json['phone'] : null,
                'branch'        => $json['branch'],
                'level'         => $json['level']
            ]);
            $student->etuutt_access_token = $access_token;
            $student->etuutt_refresh_token = $refresh_token;

            // Error here a ignored, we just keep user without a picture if we cannot download it
            $picture = @file_get_contents('http://local-sig.utt.fr/Pub/trombi/individu/' . $student->student_id . '.jpg');
            @file_put_contents(public_path() . '/uploads/students-trombi/' . $student->student_id . '.jpg', $picture);

            if ($json['sex'] == 'male') {
                $student->sex = Student::SEX_MALE;
            } elseif ($json['sex'] == 'female') {
                $student->sex = Student::SEX_FEMALE;
            }

            $student->last_login = new \DateTime();
            $student->save();
        }
        // Account was created by another person, update it with personnal informations
        elseif (empty($student->etuutt_refresh_token)) {
            $student->last_login = new \DateTime();
            $student->etuutt_access_token = $access_token;
            $student->etuutt_refresh_token = $refresh_token;
            $student->phone = ($json['phonePrivacy'] == 'public') ? $json['phone'] : null;
            if ($json['sex'] == 'male') {
                $student->sex = Student::SEX_MALE;
            } elseif ($json['sex'] == 'female') {
                $student->sex = Student::SEX_FEMALE;
            }
            $student->save();
        }
        // Else only update login datetime
        else {
            $student->last_login = new \DateTime();
            $student->etuutt_access_token = $access_token;
            $student->etuutt_refresh_token = $refresh_token;
            $student->save();
        }
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

        $this->updateUser($json, $access_token, $refresh_token);

        // Remember the user accross the whole website.
        $student = Student::where('etuutt_login', $json['login'])->where('is_newcomer', false)->first();
        Auth::login($student, true);

        return Redirect::route('menu');
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
            return Response::json(["message" => "misssing parameter : authorization_code"], 401);
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

        $this->updateUser($json, $access_token, $refresh_token);

        // generate auth token for this student
        $student = Student::where('etuutt_login', $json['login'])->first();
        $createdToken = $student->createToken("etu utt");
        $passport_access_token = $createdToken->accessToken;

        return Response::json([
            "access_token" => $passport_access_token
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
