<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Request;
use Response;
use Validator;
use Redirect;

use App\Models\Perm;
use App\Models\PermType;

class PermController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $perms = Perm::all();
        return view('dashboard.perms.index', compact('perms'));
    }

    /**
     * Show the form for choosing a type
     *
     * @return \Illuminate\Http\Response
     */
    public function selectType()
    {
        $permTypes = PermType::all();
        return view('dashboard.perms.selectType', compact('permTypes'));
    }

    /**
     * Show the form for creating a new resource with the desired type.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $validator = Validator::make(Request::all(), ['permType' => 'exists:perm_types,id']);
        if ($validator->fails()) {
            return Redirect::back()->withErrors($validator);
        }
        $permType = PermType::find(Request::get('permType'));
        return view('dashboard.perms.create', compact('permType'));
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
        $validator = Validator::make(Request::all(), Perm::webStoreRules());
        if ($validator->fails()) {
            return Redirect::back()->withErrors($validator);
        }
        $perm = new Perm();
        $perm->description = Request::get('description');
        $perm->start = $this->formatDate(Request::get('start_date'), Request::get('start_hour'));
        $perm->end = $this->formatDate(Request::get('end_date'), Request::get('end_hour'));
        $perm->place = Request::get('place');
        $perm->nbr_permanenciers = Request::get('nbr_permanenciers');
        $perm->free_join = Request::has('free_join');
        $perm->perm_type_id = Request::get('perm_type_id');
        $perm->save();
        $perm->respos()->attach(Request::get('users'), ['respo' => true]);
        return redirect('dashboard/perm');
    }


    private function formatDate($date, $hour)
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
        $perm = Perm::find($id);
        return view('dashboard.perms.edit', compact('perm'));
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
        $validator = Validator::make(Request::all(), Perm::webUpdateRules($id));
        if ($validator->fails()) {
            return Redirect::back()->withErrors($validator);
        }
        $perm = Perm::find($id);
        $perm->description = Request::get('description');
        $perm->start = $this->formatDate(Request::get('start_date'), Request::get('start_hour'));
        $perm->end = $this->formatDate(Request::get('end_date'), Request::get('end_hour'));
        $perm->place = Request::get('place');
        $perm->nbr_permanenciers = Request::get('nbr_permanenciers');
        $perm->free_join = Request::has('free_join');
        $perm->perm_type_id = Request::get('perm_type_id');
        $perm->save();
        $perm->respos()->sync(Request::get('users'));

        return redirect('dashboard/perm');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        Perm::destroy($id);
        return redirect('dashboard/perm');
    }
}
