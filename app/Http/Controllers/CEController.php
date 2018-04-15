<?php

namespace App\Http\Controllers;

use App\Models\Student;
use App\Models\Team;
use EtuUTT;
use Request;
use Config;
use View;

class CEController extends Controller
{
    /**
     * Set student as CE and redirect to dashboard index
     *
     * @return Response
     */
    public function firstTime()
    {
        $student = EtuUTT::student();
        $student->ce = true;
        $student->save();

        return redirect(route('dashboard.index'));
    }

    /**
     * List all the teams and show a creation form.
     *
     * @return Response
     */
    public function teamList()
    {
        return View::make('dashboard.ce.teamlist', [
            'teams' => Team::all(),
            'teamLeft' => Config::get('services.ce.maxteam') - Team::count(),
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
        $data = array('name'=>null);
        $team = Team::create($data);
        $team->respo_id = EtuUTT::student()->id;
        if ($team->save()) {
            // Put user in the team
            $student = EtuUTT::student();
            $student->ce = true;
            $student->team_id = $team->id;
            $student->team_accepted = true;
            if ($student->save()) {
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
            'team' => EtuUTT::student()->team,
            'student' => EtuUTT::student(),
            'newcomers' => EtuUTT::student()->team->newcomers,
        ]);
    }

    /**
     * Submit team information form
     *
     * @return Response
     */
    public function myTeamSubmit()
    {
        $team = EtuUTT::student()->team;
        $data = Request::only(['name', 'description', 'img', 'facebook']);
        $this->validate(Request::instance(), [
            'name' => 'required|min:3|max:30|unique:teams,name,'.EtuUTT::student()->team->id,
            'description' => 'min:250',
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
        // If the user is new, import some values from the API response.
        $json = EtuUTT::call('/api/public/users/'.$login)['data'];
        $student = Student::find($json['studentId']);
        if ($student === null) {
            $student = new Student([
                'student_id'    => $json['studentId'],
                'first_name'    => $json['firstName'],
                'last_name'     => $json['lastName'],
                'surname'       => $json['surname'],
                'email'         => $json['email'],
                'facebook'      => $json['facebook'],
                'branch'        => $json['branch'],
                'level'         => $json['level'],
                'ce'            => 1,
                'team_id'       => EtuUTT::student()->team_id,
            ]);
            $student->save();

            // Error here a ignored, we just keep user without a picture if we cannot download it
            $picture = @file_get_contents('http://local-sig.utt.fr/Pub/trombi/individu/' . $json['studentId'] . '.jpg');
            @file_put_contents(public_path() . '/uploads/students-trombi/' . $json['studentId'] . '.jpg', $picture);
        } elseif ($student->team) {
            return $this->error('Cet étudiant fait déjà partie d\'une équipe. Il faut qu\'il la quitte avant de pouvoir être ajouté à une nouvelle équipe.');
        } else {
            $student->team_id = EtuUTT::student()->team_id;
            $student->ce = 1;
            $student->save();
        }

        $team = EtuUTT::student()->team;
        $team->validated = false;
        $team->save();

        return redirect(route('dashboard.ce.myteam'))->withSuccess($json['fullName'].' a bien été ajouté à votre équipe. Il faut maintenant qu\'il se connecte au site d\'intégration pour valider sa participation.');
    }


    /**
     * Accept team invitation
     *
     * @return Response
     */
    public function join()
    {
        $student = EtuUTT::student();

        $team = $student->team;
        $team->validated = false;
        $team->save();

        $student->team_accepted = true;
        $student->save();

        return redirect(route('dashboard.ce.myteam'))->withSuccess('Vous avez rejoint l\'équipe !');
    }


    /**
     * Refuse team invitation or quit team
     *
     * @return Response
     */
    public function unjoin()
    {
        $student = EtuUTT::student();
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
