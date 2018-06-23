<?php

namespace App\Http\Controllers;

use App\Models\User;
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
    public function store()
    {
        $user = $user = Auth::guard('api')->user();

        if (!$user->admin && !$user->secu) {
            return Response::json(["message" => "You are not allowed."], 403);
        }

        // validate the request inputs
        $validator = Validator::make(Request::all(), $this->storeRules());
        if ($validator->fails()) {
            return Response::json(["errors" => $validator->errors()], 400);
        }

        $requestTargets = Request::get('targets');
        $notificationTargets = User::whereNotNull('device_token');
        if (!in_array("all", $requestTargets)) {
            $notificationTargets = $notificationTargets->where($requestTargets[0], '>', 0);
            for ($i = 1; $i < sizeof($requestTargets); $i++) {
                $notificationTargets = $notificationTargets->orWhere($requestTargets[$i], '>', 0);
            }
        }

        $notificationTargets = $notificationTargets->pluck('device_token')->toArray();

        $this->postNotification($notificationTargets, Request::get('message'), Request::get('title'));

        return Response::json();
    }

    /**
     * Define the rules that check if the parameters of a request
     * to create a new NotificationController are valid.
     */
    private static function storeRules()
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

}
