<?php

/**
 *
 * @author  Thomas Chauchefoin <thomas@chauchefoin.fr>
 * @license MIT
 */
class NewcomersController extends \BaseController {

    /**
     * Show the list of the newcomers.
     *
     * @return Response
     */
    public function index()
    {
        return View::make('dashboard.newcomers.index', [
            'newcomers' => Newcomer::all()
        ]);
    }

    /**
     * Display a profile.
     *
     * @param  int $id
     * @return Response
     */
    public function show($id)
    {
        $newcomer = Newcomer::findOrFail($id);

        return View::make('letter', [ 'newcomer' => $newcomer ]);
    }

}
