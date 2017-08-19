<?php

namespace App\Traits;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;

trait PushNotifications {

    /**
     * Make a post create on the ionic's push notifications api
     * to create a new push notification
     *
     * @param array $tokens: the devices tokens
     * @param string $message: the notification message
     */
    public function postNotification($tokens, $message)
    {
        $client = new Client();
        $result = $client->post('https://api.ionic.io/push/notifications', [
            "body" => json_encode([
                "tokens" => $tokens,
                "profile" => env("IONIC_PUSH_PROFILE"),
                "notification" => [
                    "message" => $message
                ]
            ]),
            "headers" => [
                'content-type' => 'application/json',
                "Authorization" => "Bearer ".env("IONIC_API_TOKEN")
            ]
        ]);
    }

}
