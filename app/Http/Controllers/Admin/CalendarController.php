<?php

namespace App\Http\Controllers\Admin;

use App\Models\Event;
use App\Models\Perm;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class CalendarController extends Controller
{
    public function index(Request $request)
    {
        $perms = Perm::all();
        $events = Event::all();

        $calendar = \Calendar::addEvents($perms)->addEvents($events);
        return view('dashboard.calendar.index', compact('calendar'));
    }
}
