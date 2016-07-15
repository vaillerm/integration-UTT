<?php

namespace App\Http\Controllers;

use App\Models\Newcomer;
use Request;
use View;

/**
 *
 * @author  Thomas Chauchefoin <thomas@chauchefoin.fr>
 * @license MIT
 */
class NewcomersController extends Controller
{

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

    /**
     * Create a newcomer.
     *
     * @return Response
     */
    public function create()
    {
        $newcomer = Newcomer::create(Request::all());
        if ($newcomer->save()) {
            return $this->success('L\'utilisateur a été créé !');
        }
        return $this->error('Impossible de créer l\'utilisateur !');
    }
}
