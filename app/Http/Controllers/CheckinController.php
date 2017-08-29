<?php

namespace App\Http\Controllers;

use App\Models\Checkin;
use App\Models\Student;

use Request;
use Response;
use Validator;
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
        $user = $user = Auth::guard('api')->user();

        if (!$user->admin && !$user->secu && !$user->ce && !$user->orga) {
            return Response::json(["message" => "You are not allowed."], 403);
        }

        return Response::json(Checkin::all());
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
     * Store a new checkin
     *
     * @return Response
     */
    public function store()
    {
        $user = $user = Auth::guard('api')->user();

        if (!$user->ce && !$user->orga) {
            return Response::json(["message" => "You are not allowed."], 403);
        }

        // validate the request inputs
        $validator = Validator::make(Request::all(), Checkin::storeRules());
        if ($validator->fails()) {
            return Response::json(["errors" => $validator->errors()], 400);
        }

        $checkin = Checkin::create(Request::all());
        return Response::json($checkin);
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

        // attach only if not already attached
        if (!$checkin->students->contains($student->id)) {
            $checkin->students()->attach($student->id);
        }

        return Response::json(Checkin::with('students')->find($id));
    }

}
