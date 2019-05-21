<?php

namespace App\Classes;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Session;
use Config;
use App;

class EtuUTT
{
    /**
     * Call an endpoit on EtuUTT API and renew token if expired
     *
     * @return array
     */
    public function call($path, $params = [])
    {
        $client = new \GuzzleHttp\Client([
            'base_uri' => Config::get('services.etuutt.baseuri.api'),
            'auth' => [
                Config::get('services.etuutt.client.id'),
                Config::get('services.etuutt.client.secret')
            ]
        ]);

        try {
            $response = $client->get($path.'?access_token=' . Auth::user()->etuutt_access_token.'&'.http_build_query($params));
        } catch (\GuzzleHttp\Exception\ClientException $e) {
            if ($e->hasResponse()) {
                $json = json_decode($e->getResponse()->getBody()->getContents(), true);
                if ($json) {

                    // Catch token expiration
                    if (isset($json['error']) && $json['error'] == 'expired_token') {
                        // Refresh token
                        $params = [
                            'grant_type'         => 'refresh_token',
                            'refresh_token' => Auth::user()->etuutt_refresh_token
                        ];
                        try {
                            $response = $client->post('/api/oauth/token', ['form_params' => $params]);
                        } catch (\GuzzleHttp\Exception\GuzzleException $e) {
                            // An error 400 from the server is usual when the authorization_code
                            // has expired. We force deconnexion to let hom renew his token
                            if ($e->hasResponse() && $e->getResponse()->getStatusCode() === 400) {
                                Session::flush();
                            }
                            App::abort(500);
                        }

                        $json = json_decode($response->getBody()->getContents(), true);
                        $student = Auth::user();
                        $student->etuutt_access_token = $json['access_token'];
                        $student->etuutt_refresh_token = $json['refresh_token'];
                        $student->save();

                        return $this->call($path, $params);
                    } else {
                        $json['http_code'] = $e->getCode();
                        return $json;
                    }
                }
            }
            return [
                'http_code' => $e->getCode(),
                'error' => $e->getCode(),
                'error_message' => $e->getResponse()->getReasonPhrase(),
            ];
        } catch (\GuzzleHttp\Exception\GuzzleException $e) {
            App::abort(500);
        }

        $json = json_decode($response->getBody()->getContents(), true);

        return $json;
    }


    /**
     * Find and update or create user from etuutt user data
     * @param   $json          Etuutt user dat
     * @param   $access_token  EtuUTT Access token
     * @param   $refresh_token EtuUTT Refresh token
     * @return                 found or created user
     */
    public function updateOrCreateUser($json, $access_token, $refresh_token)
    {
        // If the user is new, import some values from the API response.
        $student = User::where('etuutt_login', $json['login'])->first();
        if ($student === null) {
            // Fallback on student_id auth
            $student = User::where('student_id', $json['studentId'])->first();
        }

        if ($student === null) {
            $student = new User([
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
            $student->etuutt_login = $json['login'];

            // Error here a ignored, we just keep user without a picture if we cannot download it
            $picture = @file_get_contents('https://local-sig.utt.fr/Pub/trombi/individu/' . $student->student_id . '.jpg');
            @file_put_contents(public_path() . '/uploads/students-trombi/' . $student->student_id . '.jpg', $picture);

            if ($json['sex'] == 'male') {
                $student->sex = User::SEX_MALE;
            } elseif ($json['sex'] == 'female') {
                $student->sex = User::SEX_FEMALE;
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
                $student->sex = User::SEX_MALE;
            } elseif ($json['sex'] == 'female') {
                $student->sex = User::SEX_FEMALE;
            }
            $student->save();
        }
        // Else only update login datetime
        else {
            $student->last_login = new \DateTime();
            $student->etuutt_access_token = $access_token;
            $student->etuutt_refresh_token = $refresh_token;
            $student->etuutt_login = $json['login'];
            $student->student_id = $json['studentId'];
            $student->first_name = $json['firstName'];
            $student->last_name = $json['lastName'];
            $student->save();
        }

        return $student;
    }

    /**
     * Import user from EtuUTT to the db
     * @param  string $login User etuutt login
     * @return The user entity if created or found existing
     */
    function importUser($login) {
        // If the user is new, import some values from the API response.
        $json = EtuUTT::call('/api/public/users/'.$login)['data'];
        $user = User::where([ 'etuutt_login' => $json['login'] ])->first();
        if ($user === null) {
            $user = new User([
                'student_id'    => $json['studentId'],
                'first_name'    => $json['firstName'],
                'last_name'     => $json['lastName'],
                'surname'       => $json['surname'],
                'email'         => $json['email'],
                'facebook'      => $json['facebook'],
                'branch'        => $json['branch'],
                'level'         => $json['level'],
            ]);
            $user->etuutt_login = $json['login'];
            $user->save();

            // Error here a ignored, we just keep user without a picture if we cannot download it
            $picture = @file_get_contents('http://local-sig.utt.fr/Pub/trombi/individu/' . $json['studentId'] . '.jpg');
            @file_put_contents(public_path() . '/uploads/students-trombi/' . $json['studentId'] . '.jpg', $picture);
        }

        return $user;
    }
}
