<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Classes\NewcomerMatching;
use App\Models\Team;
use Request;
use View;
use Redirect;
use EtuUTT;
use Auth;
use Response;

/**
 * Team management.
 *
 * @author  Thomas Chauchefoin <thomas@chauchefoin.fr>
 * @license MIT
 */
class TeamsController extends Controller
{

    /**
     * REST API method: GET on Team model
     *
     * @return Response
     */
    public function index()
    {
        $user = Auth::guard('api')->user();

        if ($user->admin) {
            return Response::json(Team::with('ce', 'respo', 'faction')->get());
        }

        return Response::json(["message" => "You are not allowed."], 403);
    }

    /**
     * Return the specified resource.
     *
     * @param  string  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $user = Auth::guard('api')->user();

        // if the requested team is the user's team, or if the user has rights, return the requested team
        if ($user->admin) {
            return Response::json(Team::select(['id', 'name', 'description', 'img', 'facebook', 'respo_id', 'validated', 'faction_id'])
                ->where('id', $id)->with('newcomers', 'ce', 'respo', 'faction')->first());
        }
        else if($id == $user->team_id) {
            return Response::json(Team::select(['id', 'name', 'description', 'img', 'facebook', 'respo_id', 'validated', 'faction_id'])
                ->where('id', $id)->with('ce', 'respo', 'faction')->first());
        }

        return Response::json(array(["message" => "not allowed"]), 403);
    }
}
