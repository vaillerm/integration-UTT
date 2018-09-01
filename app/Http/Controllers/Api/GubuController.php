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
     * Get the given gubu part metadat
     *
     * @param  string  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $part = GubuPart::findOrFail($id);
        return [
            'id' => $part->id,
            'order' => $part->order,
            'name' => $part->name,
            'link' => route('api.gubu.pdf', ['payload' => encrypt([
                'id' => $part->id,
                'expiration' => (new \Datetime)->add(new \DateInterval('PT5M')),
            ])]),
        ];
    }

    /**
     * Get the given gubu pdf
     *
     * @param  string  $id
     * @return \Illuminate\Http\Response
     */
    public function download($payload)
    {
        $data = decrypt($payload);
        $part = GubuPart::findOrFail($data['id']);

        if (new \Datetime > $data['expiration']) {
            return response('Link has expired', 403);
        }

        return Response::make($part->file, 200, [
            'Content-Type' => 'application/pdf',
        ]);
    }
}
