<?php

namespace App\Http\Controllers;

use Request;
use App\Models\Checkin;
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
        return Response::json(Checkin::find($id));
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
}
