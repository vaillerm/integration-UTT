<?php

namespace App\Http\Controllers;


use App\Http\Requests;

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
                return $this->success('Équipe ajoutée !');
            }
        }
        return $this->error('Impossible d\'ajouter l\'équipe !');
    }
}
