<?php

namespace App\Http\Controllers;

use App\Models\Message;
use App\Models\Student;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use Request;
use Response;
use Validator;
use Auth;

class MessageController extends Controller
{
    /**
     * Get all the messages
     *
     * @return Response
     */
    public function index()
    {
        $query = Message::with('student');

        // if there is a channel parameter, return only the message of this channel
        if (Request::has('channel')) {
            $query = $query->where('channel', Request::get('channel'));
        }

        return Response::json($query->get());
    }

    /**
     * Store a new message
     *
     * @return Response
     */
    public function store()
    {
        // validate the request inputs
        $validator = Validator::make(Request::all(), Message::storeRules());
        if ($validator->fails()) {
            return Response::json(["errors" => $validator->errors()], 400);
        }

        // create the message
        $message = Auth::guard('api')->user()->messages()->create(Request::all());

        $notificationTargets = Student::where('admin', '!=', 0)->pluck('device_token')->toArray();

        $client = new Client();
        $result = $client->post('https://api.ionic.io/push/notifications', [
            "body" => json_encode([
                "tokens" => $notificationTargets,
                "profile" => env("IONIC_PUSH_PROFILE"),
                "notification" => [
                    "message" => "Nouveau message dans '".Request::get('channel')."'"
                ]
            ]),
            "headers" => [
                'content-type' => 'application/json',
                "Authorization" => "Bearer ".env("IONIC_API_TOKEN")
            ],
            'debug' => true
        ]);

        return Response::json($message);
    }

}
