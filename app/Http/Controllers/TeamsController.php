<?php

namespace App\Http\Controllers;

use App\Models\Team;
use App\Models\Newcomer;
use Request;
use View;
use EtuUTT;

/**
 * Team management.
 *
 * @author  Thomas Chauchefoin <thomas@chauchefoin.fr>
 * @license MIT
 */
class TeamsController extends Controller
{

    /**
     * List all the teams and show a creation form.
     *
     * @return Response
     */
    public function list()
    {
        if (!EtuUTT::student()->isAdmin()) {
            return $this->error('Vous n\'avez pas le droit d\'accéder à cette page.');
        }

        return View::make('dashboard.teams.list', [
            'teams' => Team::all()
        ]);
    }

    /**
     * Create a new team.
     *
     * @return RedirectResponse|array
     */
    protected function create()
    {
        $data = Request::only(['name', 'description', 'img_url']);
        $team = Team::create($data);
        if ($team->save()) {
            return $this->success('Équipe ajoutée !');
        }
        return $this->error('Impossible d\'ajouter l\'équipe !');
    }

    /**
     * Show the edit form for a team.
     *
     * @param  int $id
     * @return RedirectResponse|array
     */
    public function edit($id)
    {
        $team = Team::findOrFail($id);
        return View::make('dashboard.teams.edit', [
            'team' => $team
        ]);
    }

    /**
     * Update a team.
     *
     * @param  int $id
     * @return RedirectResponse|array
     */
    public function update($id)
    {
        $team = Team::findOrFail($id);
        $data = Request::only(['name', 'description', 'img_url']);
        if ($team->update($data)) {
            return $this->success('Équipe mise à jour !');
        }
        return $this->error('Impossible de mettre à jour l\'équipe');
    }

    /**
     * Destroy a team.
     *
     * @param  int $id Team's id.
     * @return RedirectResponse|array
     */
    protected function destroy($id)
    {
        $team = Team::find($id);
        if ($team) {
            $team->delete();
            return $this->success('Équipe supprimée !');
        }
        return $this->error('Équipe inconnue !');
    }

    /**
     * Members of a team.
     *
     * @param  int $id
     * @return Response
     */
    public function members($id)
    {
        $team = Team::findOrFail($id);
        $members = $team->newcomers()->orderBy('level', 'DESC')->get();
        $withoutTeam = Newcomer::where('team_id', null)->get();
        return View::make('dashboard.teams.members', [
            'team'      => $team,
            'newcomers' => $members,
            'alones'    => $withoutTeam
        ]);
    }

    public function addMember($id)
    {
        $team = Team::find($id);
        if ($team) {
            $newcomer = Newcomer::findOrFail((int)Request::input('newcomer'));
            $newcomer->team_id = $id;
            $newcomer->save();
            return $this->success('Le nouveau a été ajouté à l\'équipe !');
        }
        return $this->error('Équipe inconnue !');
    }
}
