<?php

namespace App\Classes;

use App\Models\Team;
use App\Models\Newcomer;
use Config;

/**
 * Class that match newcomer to referral and teams
 */
class NewcomerMatching
{
    /**
     * Match all newcomer without a team to a team
     *
     * Current algorithme put user of a branch inside one of the team
     * with the same branch (because we want to have PMOM with PMOM),
     * or if there is no team with the same branch, we put them in team
     * with null as a branch.
     */
    public static function matchTeams()
    {
        $newcomers = Newcomer::whereNull('team_id')->get();

        // Create an array to branch_id with number of newcomers in the team
        $countPerTeam = [];
        $teams = Team::all();
        foreach ($teams as $team) {
            if (!isset($countPerTeam[$team->branch])) {
                $countPerTeam[$team->branch] = [];
            }
            $countPerTeam[$team->branch][$team->id] = $team->newcomers->count();
        }

        foreach ($newcomers as $newcomer) {
            // Select teams associated with newcomer's branch if exist
            $branch = null;
            if (isset($countPerTeam[$newcomer->branch])) {
                $branch = $newcomer->branch;
            }

            // find teams with less newcomers and take it randomly
            $min = min($countPerTeam[$branch]);
            $keys = array_keys($countPerTeam[$branch], $min);
            $key = array_rand($keys);

            // set the new team and tell there is another number
            $newcomer->team_id = $keys[$key];
            $countPerTeam[$branch][$keys[$key]]++;
        }

        // Save it to DB
        foreach ($newcomers as $newcomer) {
            $newcomer->save();
        }
    }
}
