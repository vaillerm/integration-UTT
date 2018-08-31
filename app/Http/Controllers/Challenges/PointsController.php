<?php

namespace App\Http\Controllers\Challenges;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Team;
use App\Http\Requests\PointsRequest;
use App\Models\Point;
use Auth;

class PointsController extends Controller
{
    public function manage() {
        $teams = Team::all();
        return view("dashboard.challenges.points", compact("teams"));
    }

    public function add(PointsRequest $request) 
    {
        $points = $request->toArray();
        $points["added_by"] = Auth::user()->id;
        Point::create($points);
        return redirect()->back()->withSuccess("points ajoutés");
    }
}
