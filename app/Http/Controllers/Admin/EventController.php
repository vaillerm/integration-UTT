<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Request;
use Response;
use Validator;
use Redirect;

use App\Models\Event;

class EventController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $query = Event::orderBy('start_at');
        // if there is a student, get only the events of this student
        if (Request::has('student')) {
            $user = User::find(Request::get('student'));

            // get the categories of this student
            $categories = [];
            if ($user->admin) {
                array_push('admin', $categories);
            }
            if ($user->ce) {
                array_push('ce', $categories);
            }
            if ($user->volunteer) {
                array_push('volunteer', $categories);
            }
            if ($user->is_newcomer) {
              if($user->branch == 'TC') {
                array_push('newcomerTC', $categories);
              }
              else {
                array_push('newcomerBranch', $categories);
              }
            }
            if ($user->referral) {
                array_push('referral', $categories);
            }
            // add where conditions for each categories
            $query = $query->where('categories', 'like', '%'.$categories[0].'%');
            for ($i = 1; $i < sizeof($categories); $i++) {
                $query = $query->orWhere('categories', 'like', '%'.$categories[0].'%');
            }
        }
        $events = $query->get();

        // handle api request
        if (Request::wantsJson()) {
            return Response::json($events);
        }

        return view('dashboard.events.index', compact('events'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('dashboard.events.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function store()
    {
        Request::flash();

        // validate the request inputs
        $validator = Validator::make(Request::all(), Event::storeRules());
        if ($validator->fails()) {
            return Redirect::back()->withErrors($validator);
        }

        $event = new Event();
        $event->name = Request::get('name');
        $event->description = Request::get('description');
        $event->place = Request::get('place');
        $event->categories = json_encode(Request::get('categories'));
        $event->start_at = $this->formatEventDate(Request::get('start_at_date'), Request::get('start_at_hour'));
        $event->end_at = $this->formatEventDate(Request::get('end_at_date'), Request::get('end_at_hour'));
        $event->save();

        return redirect('dashboard/event');
    }

    private function formatEventDate($date, $hour)
    {
        $date = implode('-', array_reverse(explode('-', $date)));
        return strtotime($date.' '.$hour);
    }


    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $event = Event::find($id);
        return view('dashboard.events.edit', compact('event'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update($id)
    {
        // validate the request inputs
        $validator = Validator::make(Request::all(), Event::storeRules());
        if ($validator->fails()) {
            return Redirect::back()->withErrors($validator);
        }

        $event = Event::find($id);
        $event->fill(Request::all());
        $event->categories = json_encode(Request::get('categories'));
        $event->start_at = $this->formatEventDate(Request::get('start_at_date'), Request::get('start_at_hour'));
        $event->end_at = $this->formatEventDate(Request::get('end_at_date'), Request::get('end_at_hour'));
        $event->save();

        return redirect('dashboard/event');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        Event::destroy($id);
        return redirect('dashboard/event');
    }
}
