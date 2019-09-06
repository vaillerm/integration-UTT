<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Request;
use Response;
use Validator;
use Redirect;

use App\Models\Team;
use App\Models\User;
use App\Models\Point;

class RallyeController extends Controller
{
    public function store($id) // team id
    {
        // api request
        $user = Auth::guard('api')->user();

        if (!$user->admin) { // add role for rallye later
            return Response::json(["message" => "You are not allowed."], 403);
        }
        $team = Team::find($id);
        $params = Request::all()['params'];
        if ($params['user'] && $params['stand'] && $params['result']) {
          // return Response::json(["message" => "ok2344"], 200);
          $amount = 0;
          switch($params['result']) {
            case 'V':
              $amount = 10;
              break;
            case 'D':
              $amount = 0;
              break;
            case 'E':
              $amount = 5;
              break;
            default:
            break;
          }
          $points = new Point();
          $points->reason = 'rallye.'.$params['stand'];
          $points->amount = $amount;
          $points->team_id = $team->id;
          $points->added_by = User::find($params['user'])->id;
          $points->save();
          return Response::json(["message" => "ok"], 200);
        }
        return Response::json(["message" => "Missing parameters"], 400);
    }
}
