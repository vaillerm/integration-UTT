<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Request;
use Response;
use Auth;

class LocalisationController extends Controller
{
    public function store()
    {
        $user = Auth::guard('api')->user();
        if(!$user->orga && !$user->admin) {
            return Response::json(["message" => "You are not allowed"], 403);
        }
        if (! Request::has('lat')) {
            return Response::json(["message" => "missing parameter : lat"], 400);
        }
        if (! Request::has('long')) {
            return Response::json(["message" => "missing parameter : long"], 400);
        }
        $user->latitude = Request::get('lat');
        $user->longitude = Request::get('long');
        $user->save();
        return Response::json(["message" => "ok"], 200);
    }
}
