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
        $data = ['title' => $title, 'message' => $message];
        $notifs = [];
        foreach ($tokens as $token) {
            array_push($notifs, [
              "to" => "ExponentPushToken[".$token."]",
              "title" => $title,
              "body" => $message,
              "data" => $data
            ]);
        }
        $client = new Client();
        $result = $client->post('https://exp.host/--/api/v2/push/send', [
            "body" => json_encode($notifs),
            "headers" => [
                'content-type' => 'application/json'
            ]
        ]);
        return $result;
    }

}
