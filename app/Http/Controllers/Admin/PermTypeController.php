<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Request;
use Response;
use Validator;
use Redirect;
use App\Models\User;

use App\Models\PermType;

class PermTypeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $permTypes = PermType::all();
        return view('dashboard.permTypes.index', compact('permTypes'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('dashboard.permTypes.create');
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
        $validator = Validator::make(Request::all(), PermType::webStoreRules());
        if ($validator->fails()) {
            return Redirect::back()->withErrors($validator);
        }

        $permType = new PermType();
        $permType->name = Request::get('name');
        $permType->description = Request::get('description');
        $permType->points = Request::get('points');
        $permType->save();
        $permType->respos()->attach(Request::get('users'));

        return redirect('dashboard/permType');
    }



    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $permType = PermType::find($id);
        return view('dashboard.permTypes.edit', compact('permType'));
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
        $validator = Validator::make(Request::all(), PermType::webUpdateRules($id));
        if ($validator->fails()) {
            return Redirect::back()->withErrors($validator);
        }
        $permType = PermType::find($id);
        $permType->fill(Request::all());
        $permType->save();
        $permType->respos()->sync(Request::get('users'));

        return redirect('dashboard/permType');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        PermType::destroy($id);
        return redirect('dashboard/permType');
    }
}
