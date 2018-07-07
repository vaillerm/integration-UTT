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
}
