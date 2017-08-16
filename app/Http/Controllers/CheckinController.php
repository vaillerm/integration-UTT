<?php

namespace App\Http\Controllers;

use App\Models\Checkin;
use App\Models\Student;

use Request;
use Response;
use Validator;

class CheckinController extends Controller
{
    public function index()
    {
        return Response::json(Checkin::all());
    }

    public function show($id)
    {
        return Response::json(Checkin::with('students')->find($id));
    }

    public function store()
    {
        // validate the request inputs
        $validator = Validator::make(Request::all(), Checkin::storeRules());
        if ($validator->fails()) {
            return Response::json(["errors" => $validator->errors()], 400);
        }

        $checkin = Checkin::create(Request::all());
        return Response::json($checkin);
    }

    public function addStudent($id)
    {
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
