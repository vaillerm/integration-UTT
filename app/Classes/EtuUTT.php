<?php

namespace App\Classes;

use App\Models\Student;
use Session;
use Config;
use App;

class EtuUTT
{

    /**
     * The currently authenticated student.
     *
     * @var \App\Models\Student
     */
    protected $student;


    /**
     * Determine if the current user is authenticated.
     *
     * @return bool
     */
    public function isAuth()
    {
        return ! is_null($this->student());
    }

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
            $response = $client->get($path.'?access_token=' . $this->student()->etuutt_access_token.'&'.http_build_query($params));
        } catch (\GuzzleHttp\Exception\ClientException $e) {
            if ($e->hasResponse()) {
                $json = json_decode($e->getResponse()->getBody()->getContents(), true);
                if ($json) {

                    // Catch token expiration
                    if (isset($json['error']) && $json['error'] == 'expired_token') {
                        // Refresh token
                        $params = [
                            'grant_type'         => 'refresh_token',
                            'refresh_token' => $this->student()->etuutt_refresh_token
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
                        $student = $this->student();
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
     * Get the currently authenticated student.
     *
     * @return \App\Models\Student|null
     */
    public function student()
    {
        // If we've already retrieved the user for the current request we can just
        // return it back immediately. We do not want to fetch the user data on
        // every call to this method because that would be tremendously slow.
        if (! is_null($this->student)) {
            return $this->student;
        }

        $id = Session::get('student_id');

        // First we will try to load the user using the identifier in the session if
        // one exists. Otherwise we will check for a "remember me" cookie in this
        // request, and if one exists, attempt to retrieve the user using that.
        $student = null;
        if (! is_null($id)) {
            $student = Student::find(Session::get('student_id'));
        }

        if ($student === null && $id !== null) {
            Session::forget('student_id');
            abort(500);
        }

        return $student;
    }

    /**
     * Search a student on EtuUTT by first_name, last_name, student_id
     *
     * @return array
     */
    public function searchStudent()
    {
        // If we've already retrieved the user for the current request we can just
        // return it back immediately. We do not want to fetch the user data on
        // every call to this method because that would be tremendously slow.
        if (! is_null($this->student)) {
            return $this->student;
        }

        $id = Session::get('student_id');

        // First we will try to load the user using the identifier in the session if
        // one exists. Otherwise we will check for a "remember me" cookie in this
        // request, and if one exists, attempt to retrieve the user using that.
        $student = null;
        if (! is_null($id)) {
            $student = Student::find(Session::get('student_id'));
        }

        if ($student === null && $id !== null) {
            Session::forget('student_id');
            abort(500);
        }

        return $student;
    }
}
