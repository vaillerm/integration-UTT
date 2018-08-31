<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Checkin;
use App\Models\User;

use Request;
use Response;
use Validator;
use Redirect;
use Auth;

class CheckinController extends Controller
{
    /**
     * Get all the checkins
     *
     * @return Response
     */
    public function index()
    {
        $checkins = Checkin::all();
        return view('dashboard.checkins.index', compact('checkins'));
    }


    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('dashboard.checkins.create');
    }

    /**
     * Store a new checkin
     *
     * @return Response
     */
    public function store()
    {
        // validate the request inputs
        $validator = Validator::make(Request::all(), Checkin::webStoreRules());
        if ($validator->fails()) {
            return Redirect::back()->withErrors($validator);
        }

        $checkin = Checkin::create(array_merge(Request::all(), ['prefilled' => true]));
        $checkin->users()->attach(Request::get('users'));
        return redirect('dashboard/checkin');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $checkin = Checkin::find($id);
        return view('dashboard.checkins.edit', compact('checkin'));
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
        $validator = Validator::make(Request::all(), Checkin::webUpdateRules($id));
        if ($validator->fails()) {
            return Redirect::back()->withErrors($validator);
        }

        $checkin = Checkin::find($id);
        $checkin->fill(Request::all());
        $checkin->save();
        $checkin->users()->sync(Request::get('users'));

        return redirect('dashboard/checkin');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        Checkin::destroy($id);
        return redirect('dashboard/checkin');
    }

}
