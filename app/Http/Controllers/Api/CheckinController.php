<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Checkin;
use App\Models\User;

use Request;
use Response;
use Validator;
use Auth;

class CheckinController extends Controller
{
    /**
     * Get all the checkins
     *
     * @return Response
     */
    public function index()
    {
        // api request
        $user = $user = Auth::guard('api')->user();

        if (!$user->admin && !$user->secu && !$user->ce && !$user->orga) {
            return Response::json(["message" => "You are not allowed."], 403);
        }
        return Response::json(Checkin::all());
    }

    /**
     * Get a checkin, with his relationships
     *
     * @return Response
     */
    public function show($id)
    {
        $user = $user = Auth::guard('api')->user();

        if (!$user->admin && !$user->secu && !$user->ce && !$user->orga) {
            return Response::json(["message" => "You are not allowed."], 403);
        }

        return Response::json(Checkin::with('users')->find($id));
    }

    /**
     * Store a new checkin
     *
     * @return Response
     */
    public function store()
    {
        // api request
        $user = $user = Auth::guard('api')->user();

        if (!$user->ce && !$user->orga && !$user->admin) {
            return Response::json(["message" => "You are not allowed."], 403);
        }

        // validate the request inputs
        $validator = Validator::make(Request::all(), Checkin::apiStoreRules());
        if ($validator->fails()) {
            return Response::json(["errors" => $validator->errors()], 400);
        }

        $checkin = Checkin::create(Request::all());
        return Response::json($checkin);
    }

    /**
     * Attach a user to a Checkin
     *
     * @param string $id: the checkin id
     * @return Response
     */
    public function addUser($id)
    {
        $user = $user = Auth::guard('api')->user();

        if (!$user->ce && !$user->orga && !$user->admin) {
            return Response::json(["message" => "You are not allowed."], 403);
        }
        // validate the request inputs
        $validator = Validator::make(Request::all(), Checkin::addUserRules());
        if ($validator->fails()) {
            return Response::json(["errors" => $validator->errors()], 400);
        }

        $checkin = Checkin::find($id);
        if (!$checkin) {
            return Response::json(["message" => "Can't find this Checkin."], 404);
        }

        // the email is already check by the validator, so this user exists

        $user = User::where('qrcode', Request::get('uid'))->firstOrFail();

        if ($checkin->prefilled) {
            // if user is admin and there is a force attribute, add the user to the prefilled checkin
            if (Request::has('force') && Request::get('force')) {
                $checkin->users()->attach($user->id);
            }
            // prefilled checkin, so we just have to set check to true in pivot table
            if ($checkin->users->contains($user->id)) {
                $checkin->users()->sync([$user->id => ['checked' => true] ], false);
            } else {
                // try to check a user who is not in the prefilled user
                return Response::json(["message" => "Pas dans la liste."], 403);
            }
        } else {
            // attach the user to the checkin
            if (!$checkin->users->contains($user->id)) {
                $checkin->users()->attach($user->id);
                $checkin->users()->sync([$user->id => ['checked' => true] ], false);
            }
        }

        return Response::json('OK');
    }

    /**
     * Attach a user to a Checkin
     *
     * @param string $id: the checkin id
     * @return Response
     */
    public function removeUser($id)
    {
        $user = $user = Auth::guard('api')->user();

        if (!$user->ce && !$user->orga && !$user->admin) {
            return Response::json(["message" => "You are not allowed."], 403);
        }
        // validate the request inputs
        $validator = Validator::make(Request::all(), Checkin::addUserRules()); // same rules than add
        if ($validator->fails()) {
            return Response::json(["errors" => $validator->errors()], 400);
        }

        $checkin = Checkin::find($id);
        if (!$checkin) {
            return Response::json(["message" => "Can't find this Checkin."], 404);
        }

        // the email is already check by the validator, so this user exists

        $user = User::where('qrcode', Request::get('uid'))->firstOrFail();

        if ($checkin->users->contains($user->id)) {
            $checkin->users()->sync([$user->id => ['checked' => false] ], false);
        } else {
            // try to uncheck a user who is not in the prefilled user
            return Response::json(["message" => "Pas dans la liste."], 403);
        }
        return Response::json('OK');
    }
}
