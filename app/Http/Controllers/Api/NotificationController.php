<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Device;
use Illuminate\Validation\Rule;

use Request;
use Response;
use Validator;
use Auth;

use App\Traits\PushNotifications;

class NotificationController extends Controller
{

    use PushNotifications;

    /**
     * Store a new message
     *
     * @return Response
     */
    public function send()
    {
        $user = Auth::guard('api')->user();

        if (!$user->admin && !$user->secu) {
            return Response::json(["message" => "You are not allowed."], 403);
        }

        // validate the request inputs
        $validator = Validator::make(Request::all(), $this->sendRules());
        if ($validator->fails()) {
            return Response::json(["errors" => $validator->errors()], 400);
        }

        $requestTargets = Request::get('targets');
        $notificationTargets = User::whereHas('devices');
        if (!in_array("all", $requestTargets)) {
            $notificationTargets = $notificationTargets->where($requestTargets[0], '>', 0);
            for ($i = 1; $i < sizeof($requestTargets); $i++) {
                $notificationTargets = $notificationTargets->orWhere($requestTargets[$i], '>', 0);
            }
        }

        $targets = $notificationTargets->get();
        $devices = [];
        foreach($targets as $target) {
          foreach($target->devices as $device) {
            array_push($devices, $device->push_token);
          }
        }
        $this->postNotification($devices, Request::get('message'), Request::get('title'));

        return Response::json();
    }

    /**
     * Store a new message
     *
     * @return Response
     */
    public function store()
    {
        $user = Auth::guard('api')->user();
        // validate the request inputs
        $validator = Validator::make(Request::all(), $this->storeRules());
        if ($validator->fails()) {
            return Response::json(["errors" => $validator->errors()], 400);
        }

        $device_name = Request::get('device');
        $device_uid = Request::get('device_uid');
        $push_token = Request::get('push_token');
        $found = null;
        foreach($user->devices as $device) {
          if($device->uid == $device_uid) $found = $device;
        }
        if($found != null) {
          $device = $found;          
          $device->name = $device_name;
          $device->push_token = $push_token;
          $device->save();
        } else {
            $device = new Device([
                'uid' => $device_uid,
                'name' => $device_name,
                'push_token' => $push_token,
            ]);
            $device->user_id = $user->id;
            $device->save();
        }
        

        return Response::json();
    }

    /**
     * Define the rules that check if the parameters of a request
     * to create a new NotificationController are valid.
     */
    private static function sendRules()
    {
        return [
			'targets' => 'required|array|between:1,5',
            'targets.*' => [
                Rule::in(['all', 'admin', 'orga', 'ce', 'is_newcomer'])
            ],
			'title' => 'required|string',
			'message' => 'required|string',
		];
    }
    /**
     * Define the rules that check if the parameters of a request
     * to create a new NotificationController are valid.
     */
    private static function storeRules()
    {
        return [
			'device' => 'required|string',
			'device_uid' => 'required|string',
			'push_token' => 'required|string',
		];
    }

}
