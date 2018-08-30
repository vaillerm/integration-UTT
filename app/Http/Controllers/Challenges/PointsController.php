<?php

namespace App\Http\Controllers\Challenges;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class PointsController extends Controller
{
    public function manage() {
        return view("dashboard.challenges.points");
    }
}
