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
        $points = Point::orderBy("created_at", "desc")->get();
        return view("dashboard.challenges.points", compact("teams", "points"));
    }

    public function add(PointsRequest $request) 
    {
        $points = $request->toArray();
        $points["added_by"] = Auth::user()->id;
        Point::create($points);
        return redirect()->back()->withSuccess("points ajoutés");
    }

    public function delete(int $pointId)
    {
        Point::where("id", "=", $pointId)->delete();
        return redirect()->back()->withSuccess("le point a été supprimé");
    }
}
