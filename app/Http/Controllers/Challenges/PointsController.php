<?php

namespace App\Http\Controllers\Challenges;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Team;

class PointsController extends Controller
{
    public function manage() {
        $teams = Team::all();
        return view("dashboard.challenges.points", compact("teams"));
    }
}
