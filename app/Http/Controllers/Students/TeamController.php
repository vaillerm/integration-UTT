<?php

namespace App\Http\Controllers\Students;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Team;
use EtuUTT;
use Request;
use Config;
use Authorization;
use View;
use Auth;
use App\Models\ChallengeValidation;

class TeamController extends Controller
{
    /**
     * Set student as CE and redirect to dashboard index
     *
     * @return Response
     */
    public function firstTime()
    {
        $user = Auth::user();
        $user->ce = true;
        $user->save();

        return redirect(route('dashboard.index'));
    }

    /**
     * List all the teams and show a creation form.
     *
     * @return Response
     */
    public function teamList()
    {
        $teams = Team::all();
        $countTC = 0;
        $countBranch = 0;
        foreach ($teams as $t) {
            if($t->respo->branch == "TC" && $t->respo->level < 4){
                $countTC ++;
            }
            else
                $countBranch++;
        }
        //info("Nombre de team de TC : " . $countTC . " Nombre de team de Branche : " . $countBranch);

        return View::make('dashboard.ce.teamlist', [
            'teams' => Team::all(),
            'teamLeftTC' => Config::get('services.ce.maxTeamTc') - $countTC,
            'teamLeftBranch' => Config::get('services.ce.maxTeamBranch') - $countBranch,
        ]);
    }

    /**
     * List all the teams and show a creation form.
     *
     * @return Response
     */
    public function teamCreate()
    {
        // Create team
        $team = new Team([
            'name' => null,
        ]);

        $team->respo_id = Auth::user()->id;
        if ($team->save()) {
            // Put user in the team
            $user = Auth::user();
            $user->ce = true;
            $user->team_id = $team->id;
            $user->team_accepted = true;
            if ($user->save()) {
                return redirect(route('dashboard.ce.myteam'))->withSuccess('L\'équipe a été créé !');
            }
        }
        return $this->error('Impossible d\'ajouter l\'équipe !');
    }

    /**
     * SHow student's team and form to edit it.
     *
     * @return Response
     */
    public function myTeam()
    {
        $now =  new \DateTime();
        $ceFakeDeadline = (new \DateTime(Config::get('services.ce.fakeDeadline')))->diff($now);
        return View::make('dashboard.ce.myteam', [
            'team' => Auth::user()->team,
            'student' => Auth::user(),
        ]);
    }

    /**
     * Submit team information form
     *
     * @return Response
     */
    public function myTeamSubmit()
    {
        if(!Authorization::can('ce', 'editName')) {
            return redirect(route('dashboard.ce.myteam'))->withError('Vous ne pouvez pas modifier les informations de l\'equipe');
        }

        $team = Auth::user()->team;
        $data = Request::only(['name', 'safe_name', 'description', 'img', 'facebook']);
        $this->validate(Request::instance(), [
            'name' => 'min:3|max:30|unique:teams,name,'.Auth::user()->team->id,
            'safe_name' => 'min:3|max:30|unique:teams,safe_name,'.Auth::user()->team->id,
            'description' => 'min:250',
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
                $imageError = 'Cependant l\'image n\'a pas pus être sauvegardé car elle a une taille différente d\'un carré de 200px par 200px. Veuillez la redimensionner.';
            }
        }

        // Update team informations
        $team->name = $data['name'];
        $team->safe_name = $data['safe_name'];
        $team->description = $data['description'];
        $team->facebook = $data['facebook'];
        $team->validated = false;

        if ($extension) {
            $team->img = $extension;
        }

        $team->save();

        if ($imageError) {
            return redirect(route('dashboard.ce.myteam'))->withError('Vos modifications ont été sauvegardées. '.$imageError);
        }
        return redirect(route('dashboard.ce.myteam'))->withSuccess('Vos modifications ont été sauvegardées.');
    }

    /**
     * Search for a student to add it to my team
     *
     * @return Response
     */
    public function add()
    {
        // Search student on EtuUTT
        $data = Request::only(['search']);
        $usersAssoc = [];
        $search = '';
        if ($data && !empty($data['search'])) {
            $search = $data['search'];
            $explode = explode(' ', $search, 5);
            $users = [];

            // Search every string as lastname and firstname
            $users = EtuUTT::call('/api/public/users', [
                'multifield' => $search
            ])['data'];

            // Remove duplicata and put them at the beginning
            $usersAssoc = [];
            foreach ($users as $key => $value) {
                // put rel as key in the _link array
                $value['links'] = [];
                foreach ($value['_links'] as $link) {
                    $value['links'][$link['rel']] = $link['uri'];
                }
                // If duplication
                if (isset($usersAssoc[$value['login']])) {
                    // Remove every values
                    unset($usersAssoc[$value['login']]);
                    // Put it at the beginning
                    $usersAssoc = array_merge([$value['login'] => $value], $usersAssoc);
                }
                // add it if student student remove it
                elseif ($value['isStudent'] == 1) {
                    $usersAssoc[$value['login']] = $value;
                }
            }
        }

        return View::make('dashboard.ce.add', [
            'search' => $search,
            'students' => $usersAssoc
        ]);
    }


    /**
     * When search form from add is submited
     *
     * @return Response
     */
    public function addSubmit($login)
    {
        $user = $student = EtuUTT::importUser($login);

        if ($user->team_id) {
            return $this->error('Cet étudiant fait déjà partie d\'une équipe. Il faut qu\'il la quitte avant de pouvoir être ajouté à une nouvelle équipe.');
        } else {
            $user->team_id = Auth::user()->team_id;
            $user->ce = 1;
            $user->save();
        }

        $team = Auth::user()->team;
        $team->validated = false;
        $team->save();

        return redirect(route('dashboard.ce.myteam'))->withSuccess($user->fullName().' a bien été ajouté à votre équipe. Il faut maintenant qu\'il se connecte au site d\'intégration pour valider sa participation.');
    }


    /**
     * Accept team invitation
     *
     * @return Response
     */
    public function join()
    {
        $student = Auth::user();

        $team = $student->team;
        $team->validated = false;
        $team->save();

        $student->team_accepted = true;
        $student->save();

        return redirect(route('dashboard.ce.myteam'))->withSuccess('Vous avez rejoint l\'équipe !');
    }

    /**
     * The challenges handled by a team, no matter its status
     */
    public function challenges() {
        $team_id = Auth::user()->team_id;
        $validations = ChallengeValidation::where([
                    ["team_id", "=", $team_id],
                ])->orderBy("last_update", "asc")->get();
            $score = Team::find($team_id)->score();
        return view("dashboard.teams.challenges", compact("validations", "score"));
    }


    /**
     * Refuse team invitation or quit team
     *
     * @return Response
     */
    public function unjoin()
    {
        $student = Auth::user();
        if ($student->team_accepted) {
            return redirect(route('dashboard.ce.myteam'))->withError('Vous ne pouvez pas quitter une équipe.');
        }

        $team = $student->team;
        $team->validated = false;
        $team->save();

        $student->team_id = null;
        $student->save();

        return redirect(route('dashboard.index'))->withSuccess('Vous avez refusé de rejoindre l\'équipe !');
    }
}
