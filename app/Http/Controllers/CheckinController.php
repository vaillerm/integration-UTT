<?php

namespace App\Http\Controllers;

use App\Models\Checkin;
use App\Models\Student;

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
        // api request
        if (Request::wantsJson()) {
            $user = $user = Auth::guard('api')->user();

            if (!$user->admin && !$user->secu && !$user->ce && !$user->orga) {
                return Response::json(["message" => "You are not allowed."], 403);
            }
            return Response::json(Checkin::all());
        }

        $checkins = Checkin::where('prefilled', true)->get();
        return view('dashboard.checkins.index', compact('checkins'));
    }

    /**
     * Get a checkin, with his relationships
     *
     * @return Response
     */
    public function show($id)
    {
        $user = $user = Auth::guard('api')->user();

        if (!$user->admin && !$user->secu && !$user->ce && !$user->orga) {
            return Response::json(["message" => "You are not allowed."], 403);
        }

        return Response::json(Checkin::with('students')->find($id));
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
        // api request
        if (Request::wantsJson()) {
            $user = $user = Auth::guard('api')->user();

            if (!$user->ce && !$user->orga) {
                return Response::json(["message" => "You are not allowed."], 403);
            }

            // validate the request inputs
            $validator = Validator::make(Request::all(), Checkin::apiStoreRules());
            if ($validator->fails()) {
                return Response::json(["errors" => $validator->errors()], 400);
            }

            $checkin = Checkin::create(Request::all());
            return Response::json($checkin);
        }

        // validate the request inputs
        $validator = Validator::make(Request::all(), Checkin::webStoreRules());
        if ($validator->fails()) {
            return Redirect::back()->withErrors($validator);
        }

        $checkin = Checkin::create(array_merge(Request::all(), ['prefilled' => true]));
        $checkin->students()->attach(Request::get('students'));
        return redirect('dashboard/checkin');
    }

    /**
     * Attach a Student to a Checkin
     *
     * @param string $id: the checkin id
     * @return Response
     */
    public function addStudent($id)
    {
        $user = $user = Auth::guard('api')->user();

        if (!$user->ce && !$user->orga) {
            return Response::json(["message" => "You are not allowed."], 403);
        }

        // validate the request inputs
        $validator = Validator::make(Request::all(), Checkin::addStudentRules());
        if ($validator->fails()) {
            return Response::json(["errors" => $validator->errors()], 400);
        }

        $checkin = Checkin::find($id);
        if (!$checkin) {
            return Response::json(["message" => "Can't find this Checkin."], 404);
        }

        // the email is already check by the validator, so this student exists
        $student = Student::where('email', Request::get('email'))->first();

        if ($checkin->prefilled) {
            // if user is admin and there is a force attribute, add the user to the prefilled checkin
            if (Request::has('force') && Request::get('force') && $user->admin) {
                $checkin->students()->attach($student->id);
            }
            // prefilled checkin, so we just have to set check to true in pivot table
            if ($checkin->students->contains($student->id)) {
                $checkin->students()->sync([$student->id => ['checked' => true] ], false);
            } else {
                // try to check a student who is not in the prefilled students
                return Response::json(["message" => "Pas dans la liste."], 403);
            }
        } else {
            // attach the student to the checkin
            if (!$checkin->students->contains($student->id)) {
                $checkin->students()->attach($student->id);
                $checkin->students()->sync([$student->id => ['checked' => true] ], false);
            }
        }

        return Response::json(Checkin::with('students')->find($id));
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
        $checkin->students()->sync(Request::get('students'));

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
