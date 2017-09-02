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
     * @param string $title: the notification title (optionnal)
     */
    public function postNotification($tokens, $message, $title = "")
    {
        $client = new Client();
        $result = $client->post('https://api.ionic.io/push/notifications', [
            "body" => json_encode([
                "tokens" => $tokens,
                "profile" => env("IONIC_PUSH_PROFILE"),
                "notification" => [
                    "message" => $message,
                    "title" => $title
                ]
            ]),
            "headers" => [
                'content-type' => 'application/json',
                "Authorization" => "Bearer ".env("IONIC_API_TOKEN")
            ]
        ]);
    }

}
