<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Classes\NewcomerMatching;
use App\Models\Team;
use App\Models\User;
use App\Models\Faction;
use Request;
use View;
use Redirect;
use EtuUTT;
use Auth;
use Response;

/**
 * Faction management.
 *
 */
class FactionsController extends Controller
{
    protected function create()
    {
        return view('factions.create');
    }

    public function store()
    {
        Request::flash();

        $faction = new Faction();
        $faction->name = Request::get('name');
        $faction->save();

        return redirect('faction/leaderboard');
    }

    /**
     * Show the edit form for a team.
     *
     * @param  int $id
     * @return RedirectResponse|array
     */
    public function edit($id)
    {
        $faction = Faction::findOrFail($id);
        return View::make('factions.edit', [
            'faction' => $faction
        ]);
    }

    /**
     * Execute edit form for a team from admin dashboard
     *
     * @param  int $id
     * @return RedirectResponse|array
     */
    public function editSubmit($id)
    {
        $faction = Faction::findOrFail($id);
        $data = Request::only(['name']);
        $this->validate(Request::instance(), 
            ['name' => 'required|min:3|max:70|unique:factions,name,'.$faction->id],
            ['name.unique' => 'Cette faction existe déjà.']
        );

        // Update team informations
        $faction->name = $data['name'];

        if ($faction->save()) {
            return Redirect::back()->withSuccess('Vos modifications ont été sauvegardées.');
        }
        return Redirect::back()->withError("Impossible de sauvegarder vos modifications.");
    }
}
