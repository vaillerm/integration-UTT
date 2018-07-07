<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Request;
use Response;
use Validator;
use Redirect;

use App\Models\Event;

class EventController extends Controller
{
    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $event = Event::find($id);
        return Response::json($event);
    }
}
