<?php

namespace App\Http\Controllers\All;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Faction;

class FactionsController extends Controller
{
    public function leaderboard() {
        $factions = Faction::All();
        dd($factions);
        return view("factions.leaderboard", compact("factions"));
    }
}
