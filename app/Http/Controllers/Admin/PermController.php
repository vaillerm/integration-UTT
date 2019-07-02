<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Request;
use Response;
use Validator;
use Redirect;

use App\Models\Perm;
use App\Models\PermType;
use App\Models\User;

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
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function recap()
    {
        $users = User::has('perms')->get();
        return view('dashboard.perms.recap', compact('users'));
    }

    /**
     * Display a listing of the resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function userperms($id)
    {
        $user = User::find($id);
        return view('dashboard.perms.userperms', compact('user'));
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

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function usersindex($id)
    {
        $perm = Perm::find($id);
        return view('dashboard.perms.usersindex', compact('perm'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function useradd($id)
    {
      $perm = Perm::find($id);
      return view('dashboard.perms.useradd', compact('perm'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function userstore($id)
    {
        Request::flash();

        // validate the request inputs
        $validator = Validator::make(Request::all(), [
          'users' => 'array',
          'users.*' => 'exists:users,id',
        ]);
        if ($validator->fails()) {
            return Redirect::back()->withErrors($validator);
        }
        $perm = Perm::find($id);
        $perm->permanenciers()->attach(Request::get('users'), ['respo' => false]);
        return redirect('dashboard/perm/'.$id.'/users');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @param  int  $userid
     * @return \Illuminate\Http\Response
     */
    public function userdestroy($id, $userId)
    {
        $perm = Perm::find($id);
        $perm->permanenciers()->detach($userId);
        return redirect('dashboard/perm/'.$id.'/users');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function userpresentform($id, $userId)
    {
        $perm = Perm::find($id);
        $user = User::find($userId);
        return view('dashboard.perms.userpresent', compact('perm', 'user'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @param  int  $userId
     * @return \Illuminate\Http\Response
     */
    public function userabsentform($id, $userId)
    {
        $perm = Perm::find($id);
        $user = User::find($userId);
        return view('dashboard.perms.userabsent', compact('perm', 'user'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  int  $id
     * @param  int  $userId
     * @return \Illuminate\Http\Response
     */
    public function userabsent($id, $userId)
    {
        // validate the request inputs
        $validator = Validator::make(Request::all(), ['absence_reason' => 'string', 'commentary' => 'string', 'pointsPenalty' => 'integer']);
        if ($validator->fails()) {
            return Redirect::back()->withErrors($validator);
        }
        $perm = Perm::find($id);
        $perm->permanenciers()->updateExistingPivot($userId, [
          'presence' => 'absent',
          'absence_reason' => Request::get('absence_reason'),
          'commentary' => Request::get('commentary'),
          'pointsPenalty' => Request::get('pointsPenalty') + $perm->type->points,
        ]);

        return redirect('dashboard/perm/'.$id.'/users');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  int  $id
     * @param  int  $userId
     * @return \Illuminate\Http\Response
     */
    public function userpresent($id, $userId)
    {
        // validate the request inputs
        $validator = Validator::make(Request::all(), ['commentary' => 'string', 'pointsPenalty' => 'integer']);
        if ($validator->fails()) {
            return Redirect::back()->withErrors($validator);
        }
        $perm = Perm::find($id);
        $perm->permanenciers()->updateExistingPivot($userId, [
          'presence' => 'present',
          'absence_reason' => '',
          'commentary' => Request::get('commentary'),
          'pointsPenalty' => Request::get('pointsPenalty'),
        ]);

        return redirect('dashboard/perm/'.$id.'/users');
    }
}
