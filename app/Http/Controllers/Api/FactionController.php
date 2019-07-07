<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Response;

use App\Models\Faction;

class FactionController extends Controller
{
    public function show()
    {
      $result = [];
      foreach(Faction::all() as $faction) {
        array_push($result, $faction);
      }
        return Response::json($result);
    }
}
