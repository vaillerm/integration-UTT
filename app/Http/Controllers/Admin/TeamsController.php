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
        if (!Auth::user()->isAdmin()) {
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
        $factions = Faction::All();
        return View::make('dashboard.teams.edit', [
            'team' => $team,
            'factions' => $factions
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
        $data = Request::only(['name', 'safe_name', 'description', 'img', 'facebook', 'comment', 'branch', 'faction']);
        $uniqueName = '';
        if($data['name'] != '') {
          $uniqueName = '|unique:teams';
        }
        $uniqueSafeName = '';
        if($data['safe_name'] != '') {
          $uniqueSafeName = '|unique:teams';
        }
        $this->validate(Request::instance(), [
            'name' => 'min:3|max:70'.$uniqueName.',name,'.$team->id,
            'safe_name' => 'min:3|max:30'.$uniqueSafeName.',safe_name,'.$team->id,
            'img' => 'image',
            'facebook' => 'url'
        ],
        [
            'name.unique' => 'Ce nom d\'équipe est déjà pris.',
            'safe_name.unique' => 'Votre nom "gentil" d\'équipe est déjà pris.',
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
                $imageError = 'Cependant l\'image n\'a pas pu être sauvegardée car elle a une taille différente d\'un carré de 200px par 200px. Veuillez la redimensionner.';
            }
        }

        // Update team informations
        if($data['name'] != '') {
          $team->name = $data['name'];
        }
        if($data['safe_name'] != '') {
          $team->safe_name = $data['safe_name'];
        }
        $team->description = $data['description'];
        $team->facebook = $data['facebook'];
        $team->comment = $data['comment'];
        $team->branch = $data['branch'];
        $team->faction_id = $data['faction'];
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
        $withoutTeam = User::newcomer()->where('team_id', null)->get();
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
            $newcomer = User::newcomer()->findOrFail((int)Request::input('newcomer'));
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
        return $this->error('L\'équipe n\'a pas été trouvée !');
    }

    public function adminUnvalidate($id)
    {
        $team = Team::find($id);
        if ($team) {
            $team->validated = false;
            $team->save();
            return $this->success('L\'équipe a été désapprouvée !');
        }
        return $this->error('L\'équipe n\'a pas été trouvée !');
    }

    public function attributeFaction($id)
    {
        $team = Team::find($id);
        if($team && !$team->faction) {
            $factions = Faction::all();
            $newFaction = $factions[rand(0, count($factions)-1)]->id;
            $team->faction_id = $newFaction;
            $team->save();
            return $this->success('L\'équipe est désormais dans une faction !');
        }
        return $this->error('L\'équipe est déjà dans une faction !');
    }

    public function adminSetRespo($teamId, $studentId)
    {
        $team = Team::find($teamId);
        if (!$team) {
          return $this->error('L\'equipe n\'a pas été trouvé !');
        }
        $ce = $team->ce()->get();
        $newrespo = null;
        foreach($ce as $user) {
          if($user->id == $studentId) $newrespo = $user;
        }
        if($newrespo == null) {
          return $this->error('L\'étudiant ne fait pas parti de cette équipe !');
        }
        
        $team->respo_id = $newrespo->id;
        $team->save();
        return $this->success('L\'utilisateur est maintenant chef de son équipe !');
    }

    public function adminRemoveUser($id)
    {
        $user = User::find($id);
        if (!$user) {
          return $this->error('L\'utilisateur n\'existe pas !');
        }
        $team = $user->team()->get();
        if(!$team || !$team[0]) {
          return $this->error('L\'utilisateur n\'a pas d\'équipe !');
        }
        if ($team[0]->respo_id == $user->id) {
          return $this->error('Vous ne pouvez pas supprimer le chef de l\'équipe');
        }
        $user->team_id = null;
        $user->save();
        return $this->success('L\'utilisateur a été retiré de l\'équipe !');
    }

    public function adminDelete($id)
    {
        $team = Team::find($id);
        if ($team) {
            $team->delete();
            return $this->success('L\'équipe a été supprimé !');
        }
        return $this->error('L\'equipe n\'a pas été trouvé !');
    }

    public function matchToNewcomers()
    {
        NewcomerMatching::matchTeams();
        return redirect(route('dashboard.newcomers.list'))->withSuccess('Tous les nouveaux ont maintenant une équipe !');
    }
}
