<?php

namespace App\Http\Controllers;



use App\Models\Team;

use EtuUTT;
use Request;
use Redirect;
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
        if (!EtuUTT::student()->ce)
        {
            return $this->error('Vous n\'avez pas le droit d\'accéder à cette page.');
        }

        return View::make('dashboard.ce.teamlist', [
            'teams' => Team::all()
        ]);
    }

    /**
     * List all the teams and show a creation form.
     *
     * @return Response
     */
    public function teamCreate()
    {
        if (!EtuUTT::student()->ce || EtuUTT::student()->team()->count())
        {
            return $this->error('Vous n\'avez pas le droit d\'accéder à cette page.');
        }

        $this->validate(Request::instance(), [
            'name' => 'required|min:3|max:30|unique:teams'
        ],
        [
            'name.unique' => 'Ce nom d\'équipe est déjà pris.'
        ]);

        // Create team
        $data = Request::only(['name']);
        $team = Team::create($data);
        $team->respo_id = EtuUTT::student()->student_id;
        if ($team->save()) {
            // Put user in the team
            $student = EtuUTT::student();
            $student->ce = true;
            $student->team_id = $team->id;
            $student->team_accepted = true;
            if($student->save()) {
                return $this->redirect(route('dashboard.ce.myteam'))->withSuccess('L\'équipe a été créé !');
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
        if (!EtuUTT::student()->ce && !EtuUTT::student()->team()->count())
        {
            return $this->error('Vous n\'avez pas le droit d\'accéder à cette page.');
        }

        return View::make('dashboard.ce.myteam', [
            'team' => EtuUTT::student()->team,
            'student' => EtuUTT::student()
        ]);
    }

    /**
     * Search for a student to add it to my team
     *
     * @return Response
     */
    public function add()
    {
        if (!EtuUTT::student()->ce && !EtuUTT::student()->team()->count()
            && EtuUTT::student()->student_id == EtuUTT::student()->team->respo_id
            && EtuUTT::student()->team->ce->count < 5)
        {
            return $this->error('Vous n\'avez pas le droit d\'accéder à cette page.');
        }

        // Search student on EtuUTT
        $data = Request::only(['search']);
        if($data) {
            // TODO
        }

        return View::make('dashboard.ce.add', [
            'team' => EtuUTT::student()->team,
            'student' => EtuUTT::student()
        ]);
    }

    /**
     * When search form from add is submited
     *
     * @return Response
     */
    public function addSubmit()
    {
        if (!EtuUTT::student()->ce && !EtuUTT::student()->team()->count()
            && EtuUTT::student()->student_id == EtuUTT::student()->team->respo_id
            && EtuUTT::student()->team->ce->count < 5)
        {
            return $this->error('Vous n\'avez pas le droit d\'accéder à cette page.');
        }

        return View::make('dashboard.ce.add', [
            'team' => EtuUTT::student()->team,
            'student' => EtuUTT::student()
        ]);
    }
}
