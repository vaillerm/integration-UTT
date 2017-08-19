<?php

namespace App\Http\Controllers;

use App\Models\Message;
use App\Models\Student;

use Request;
use Response;
use Validator;
use Auth;

class MessageController extends Controller
{
    /**
     * Get all the messages
     *
     * @return Response
     */
    public function index()
    {
        $query = Message::with('student');

        // if there is a channel parameter, return only the message of this channel
        if (Request::has('channel')) {
            $query = $query->where('channel', Request::get('channel'));
        }

        return Response::json($query->get());
    }

    /**
     * Store a new message
     *
     * @return Response
     */
    public function store()
    {
        // validate the request inputs
        $validator = Validator::make(Request::all(), Message::storeRules());
        if ($validator->fails()) {
            return Response::json(["errors" => $validator->errors()], 400);
        }

        // create the message
        $message = Auth::guard('api')->user()->messages()->create(Request::all());

        return Response::json($message);
    }

}
