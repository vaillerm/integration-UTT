<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Request;
use Response;
use Validator;
use Redirect;

use App\Models\Faction;

class PointController extends Controller
{
    public function show() // team id
    {
      $result = [];
      foreach(Faction::all() as $faction) {
        array_push($result, $faction->score());
      }
        return Response::json($result);
    }
}
