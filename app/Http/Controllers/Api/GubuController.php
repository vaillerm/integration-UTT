<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Classes\NewcomerMatching;
use App\Models\GubuPart;
use Request;
use View;
use Redirect;
use EtuUTT;
use Auth;
use Response;

/**
 * Gubu view
 */
class GubuController extends Controller
{

    /**
     * Get the list of gubu parts
     *
     * @return Response
     */
    public function index()
    {
        return Response::json(GubuPart::select('id', 'order', 'name')->orderBy('order')->get());
    }

    /**
     * Get the given gubu part
     *
     * @param  string  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $part = GubuPart::findOrFail($id);
        return $part->content;
    }
}
