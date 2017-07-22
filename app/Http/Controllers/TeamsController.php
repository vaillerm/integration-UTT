<?php

namespace App\Http\Controllers;

use App\Classes\NewcomerMatching;
use App\Models\Team;
use Request;
use View;
use Redirect;
use EtuUTT;
use Auth;
use Response;

/**
 * Team management.
 *
 * @author  Thomas Chauchefoin <thomas@chauchefoin.fr>
 * @license MIT
 */
class TeamsController extends Controller
{

    /**
     * REST API method: GET on Team model
     *
     * @return Response
     */
    public function find()
    {
        $id = Request::route('id');
        $user = Auth::guard('api')->user();

        // if no id in the route parameters and user allowed, return all the teams
        if ($id == null && $user->admin) {
            // return all by default
            return Response::json(Team::with('newcomers', 'ce', 'respo')->get());
        } else {
            // if the requested team is the user's team, or if the user has rights, return the requested team
            if ($id == $user->team_id || $user->admin) {
                return Response::json(Team::where('id', $id)->with('newcomers', 'ce', 'respo')->first());
            }
        }

        return Response::json(array(["message" => "not allowed"]), 403);
    }

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
     * Execute edit form for a team from admin dashboard
     *
     * @param  int $id
     * @return RedirectResponse|array
     */
    public function editSubmit($id)
    {
        $team = Team::findOrFail($id);
        $data = Request::only(['name', 'description', 'img', 'facebook', 'comment', 'branch']);
        $this->validate(Request::instance(), [
            'name' => 'required|min:3|max:70|unique:teams,name,'.$team->id,
            'img' => 'image',
            'facebook' => 'url'
        ],
        [
            'name.unique' => 'Ce nom d\'équipe est déjà pris.'
        ]);

        // Check image size
        $extension = null;
        $imageError = '';
        if (isset($_FILES['img']) && !empty($_FILES['img']['name'])) {
            $size = getimagesize($_FILES['img']['tmp_name']);
            if ($size[0] == 200 && $size[1] == 200) {
                $extension = pathinfo($_FILES['img']['name'], PATHINFO_EXTENSION);
                move_uploaded_file($_FILES['img']['tmp_name'], public_path() . '/uploads/teams-logo/' . $team->id . '.' . $extension);
            } else {
                $imageError = 'Cependant l\'image n\'a pas pus être sauvegardé car elle a une taille différente d\'un carré de 200px par 200px. Veuillez la redimensionner.';
            }
        }

        // Update team informations
        $team->name = $data['name'];
        $team->description = $data['description'];
        $team->facebook = $data['facebook'];
        $team->comment = $data['comment'];
        $team->branch = $data['branch'];
        if ($extension) {
            $team->img = $extension;
        }

        $team->save();

        if ($imageError) {
            return Redirect::back()->withError('Vos modifications ont été sauvegardées. '.$imageError);
        }
        return redirect(route('dashboard.teams.list'))->withSuccess('Vos modifications ont été sauvegardées.');
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
        $withoutTeam = Student::newcomer()->where('team_id', null)->get();
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
            $newcomer = Student::newcomer()->findOrFail((int)Request::input('newcomer'));
            $newcomer->team_id = $id;
            $newcomer->save();
            return $this->success('Le nouveau a été ajouté à l\'équipe !');
        }
        return $this->error('Équipe inconnue !');
    }

    public function adminValidate($id)
    {
        $team = Team::find($id);
        if ($team) {
            $team->validated = true;
            $team->save();
            return $this->success('L\'équipe a été approuvée !');
        }
        return $this->error('L\'equipe n\'a pas été trouvé !');
    }

    public function adminUnvalidate($id)
    {
        $team = Team::find($id);
        if ($team) {
            $team->validated = false;
            $team->save();
            return $this->success('L\'équipe a été désapprouvée !');
        }
        return $this->error('L\'equipe n\'a pas été trouvé !');
    }

    public function matchToNewcomers()
    {
        NewcomerMatching::matchTeams();
        return redirect(route('dashboard.newcomers.list'))->withSuccess('Tous les nouveaux ont maintenant une équipe !');
    }
}
