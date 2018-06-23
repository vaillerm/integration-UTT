<?php

namespace App\Http\Controllers;

use App\Models\Message;
use App\Models\User;

use Request;
use Response;
use Validator;
use Auth;

use App\Traits\PushNotifications;

class MessageController extends Controller
{

    use PushNotifications;

    /**
     * Get all the messages
     *
     * @return Response
     */
    public function index()
    {
        $user = $user = Auth::guard('api')->user();

        // check if the user can access one of the channel
        if (!$user->admin && !$user->secu && !$user->ce && !$user->orga) {
            return Response::json(["message" => "You are not allowed."], 403);
        }

        $query = Message::with('user');

        // if there is a channel parameter, return only the message of this channel
        if (Request::has('channel') && ($user->admin || $user->secu)) {
            $query = $query->where('channel', Request::get('channel'));
        } else {
            // else, by default, only the messages on general
            $query = $query->where('channel', 'general');
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
        $user = $user = Auth::guard('api')->user();

        // check if the user can access one of the channel
        if (!$user->admin && !$user->secu && !$user->ce && !$user->orga) {
            return Response::json(["message" => "You are not allowed."], 403);
        }

        // validate the request inputs
        $validator = Validator::make(Request::all(), Message::storeRules());
        if ($validator->fails()) {
            return Response::json(["errors" => $validator->errors()], 400);
        }

        // create the message
        $message = Auth::guard('api')->user()->messages()->create(Request::all());

        // notify the members of this channel that a new message has been created
        $query = User::where('admin', '>', 0)->orWhere('orga', '>', 0);
        if (Request::get('channel') == 'general') {
            $query = $query->orWhere('ce', '>', 0);
        }
        $notificationTargets = $query->pluck('device_token')->toArray();
        $this->postNotification($notificationTargets, "Nouveau message dans '".Request::get('channel')."'");

        return Response::json(Message::with('user')->find($message->id));
    }

}
