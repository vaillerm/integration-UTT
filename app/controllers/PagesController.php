<?php

/**
 * Handle misc. pages.
 *
 * @author  Thomas Chauchefoin <thomas@chauchefoin.fr>
 * @license MIT
 */
class PagesController extends \BaseController {

    /**
     * Temporary hompage.
     *
     * @return Response
     */
    public function getHomepage()
    {
        return View::make('homepage');
    }

    /**
     * Sort of menu for the user.
     *
     * @return Response
     */
    public function getMenu()
    {
        return View::make('menu');
    }

    /**
     * Export pages.
     *
     * @return Response
     */
    public function getExports()
    {
        return View::make('dashboard.exports');
    }

    /**
     * Export the referrals and the related newcomers into a CSV file.
     *
     * @return string
     */
    public function getExportReferrals()
    {
        $referrals = Referral::orderBy('last_name')->where('validated', 1)->get();
        // Embed the referral's newcomers in the document.
        foreach ($referrals as &$referral)
        {
            for ($i=0; $i < $referral->newcomers()->count(); $i++)
            {
                $newcomer = $referral->newcomers()->get()->toArray()[$i];
                $referral['Fillot '.$i] = $newcomer['first_name'].' '.$newcomer['last_name'];
            }
        }
        return Excel::create('Parrains et fillots', function($file) use ($referrals)
        {
            $file->sheet('', function($sheet) use ($referrals)
            {
                $sheet->fromArray($referrals);
            });
        })->export('csv');
    }

    /**
     * List all the factions and their teams.
     *
     * @return Response
     */
    public function getChampionship()
    {
        return View::make('dashboard.championship.admin', [
            'factions' => Faction::all()
        ]);
    }

    /**
     * Update the new points for all the teams.
     *
     * @return RedirectResponse
     */
    public function postChampionship()
    {
        foreach (Team::whereNotNull('faction_id')->get() as $team)
        {
            $input = Input::get('team-'.$team->id);
            if ($input !== null)
            {
                $team->points = $input;
                $team->save();
            }
        }
        return $this->success('Les points ont été mis à jour !');
    }

    /**
     * @return Response
     */
    public function getScores()
    {
        return View::make('scores')->with(['factions' => Faction::all()]);
    }

}
