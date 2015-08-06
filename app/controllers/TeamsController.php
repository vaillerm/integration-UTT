<?php

/**
 * Team management.
 *
 * @author  Thomas Chauchefoin <thomas@chauchefoin.fr>
 * @license MIT
 */
class TeamsController extends \BaseController {

    /**
     * List all the teams and show a creation form.
     *
     * @return Response
     */
    public function index()
    {
        return View::make('dashboard.teams.index', [
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
        $data = Input::only(['name', 'description', 'img_url']);
        $team = Team::create($data);
        if ($team->save())
        {
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
        $data = Input::only(['name', 'description', 'img_url']);
        if ($team->update($data))
        {
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
        if ($team)
        {
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
        return View::make('dashboard.teams.members', [
            'team'    => $team,
            'newcomers' => $members
        ]);
    }

}
