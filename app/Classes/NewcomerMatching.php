<?php

namespace App\Classes;

use App\Models\Team;
use App\Models\Newcomer;
use App\Models\Student;
use Config;

/**
 * Class that match newcomer to referral and teams
 * This is not in a controller because it can be used
 * in a controller and in command line
 */
class NewcomerMatching
{

    const REGION = [
        '1' => 'Auvergne-Rhône-Alpes',
        '2' => 'Nord-Pas-de-Calais-Picardie',
        '3' => 'Auvergne-Rhône-Alpes',
        '4' => 'Provence-Alpes-Côte d\'Azur',
        '5' => 'Provence-Alpes-Côte d\'Azur',
        '6' => 'Provence-Alpes-Côte d\'Azur',
        '7' => 'Auvergne-Rhône-Alpes',
        '8' => 'Alsace-Champagne-Ardenne-Lorraine',
        '9' => 'Languedoc-Roussillon-Midi-Pyrénées',
        '10' => 'Alsace-Champagne-Ardenne-Lorraine',
        '11' => 'Languedoc-Roussillon-Midi-Pyrénées',
        '12' => 'Languedoc-Roussillon-Midi-Pyrénées',
        '13' => 'Provence-Alpes-Côte d\'Azur',
        '14' => 'Normandie',
        '15' => 'Auvergne-Rhône-Alpes',
        '16' => 'Aquitaine-Limousin-Poitou-Charentes',
        '17' => 'Aquitaine-Limousin-Poitou-Charentes',
        '18' => 'Centre-Val de Loire',
        '19' => 'Aquitaine-Limousin-Poitou-Charentes',
        '21' => 'Bourgogne-Franche-Comté',
        '22' => 'Bretagne',
        '23' => 'Aquitaine-Limousin-Poitou-Charentes',
        '24' => 'Aquitaine-Limousin-Poitou-Charentes',
        '25' => 'Bourgogne-Franche-Comté',
        '26' => 'Auvergne-Rhône-Alpes',
        '27' => 'Normandie',
        '28' => 'Centre-Val de Loire',
        '29' => 'Bretagne',
        '30' => 'Languedoc-Roussillon-Midi-Pyrénées',
        '31' => 'Languedoc-Roussillon-Midi-Pyrénées',
        '32' => 'Languedoc-Roussillon-Midi-Pyrénées',
        '33' => 'Aquitaine-Limousin-Poitou-Charentes',
        '34' => 'Languedoc-Roussillon-Midi-Pyrénées',
        '35' => 'Bretagne',
        '36' => 'Centre-Val de Loire',
        '37' => 'Centre-Val de Loire',
        '38' => 'Auvergne-Rhône-Alpes',
        '39' => 'Bourgogne-Franche-Comté',
        '40' => 'Aquitaine-Limousin-Poitou-Charentes',
        '41' => 'Centre-Val de Loire',
        '42' => 'Auvergne-Rhône-Alpes',
        '43' => 'Auvergne-Rhône-Alpes',
        '44' => 'Pays de la Loire',
        '45' => 'Centre-Val de Loire',
        '46' => 'Languedoc-Roussillon-Midi-Pyrénées',
        '47' => 'Aquitaine-Limousin-Poitou-Charentes',
        '48' => 'Languedoc-Roussillon-Midi-Pyrénées',
        '49' => 'Pays de la Loire',
        '50' => 'Normandie',
        '51' => 'Alsace-Champagne-Ardenne-Lorraine',
        '52' => 'Alsace-Champagne-Ardenne-Lorraine',
        '53' => 'Pays de la Loire',
        '54' => 'Alsace-Champagne-Ardenne-Lorraine',
        '55' => 'Alsace-Champagne-Ardenne-Lorraine',
        '56' => 'Bretagne',
        '57' => 'Alsace-Champagne-Ardenne-Lorraine',
        '58' => 'Bourgogne-Franche-Comté',
        '59' => 'Nord-Pas-de-Calais-Picardie',
        '60' => 'Nord-Pas-de-Calais-Picardie',
        '61' => 'Normandie',
        '62' => 'Nord-Pas-de-Calais-Picardie',
        '63' => 'Auvergne-Rhône-Alpes',
        '64' => 'Aquitaine-Limousin-Poitou-Charentes',
        '65' => 'Languedoc-Roussillon-Midi-Pyrénées',
        '66' => 'Languedoc-Roussillon-Midi-Pyrénées',
        '67' => 'Alsace-Champagne-Ardenne-Lorraine',
        '68' => 'Alsace-Champagne-Ardenne-Lorraine',
        '69' => 'Auvergne-Rhône-Alpes',
        '70' => 'Bourgogne-Franche-Comté',
        '71' => 'Bourgogne-Franche-Comté',
        '72' => 'Pays de la Loire',
        '73' => 'Auvergne-Rhône-Alpes',
        '74' => 'Auvergne-Rhône-Alpes',
        '75' => 'Île-de-France',
        '76' => 'Normandie',
        '77' => 'Île-de-France',
        '78' => 'Île-de-France',
        '79' => 'Aquitaine-Limousin-Poitou-Charentes',
        '80' => 'Nord-Pas-de-Calais-Picardie',
        '81' => 'Languedoc-Roussillon-Midi-Pyrénées',
        '82' => 'Languedoc-Roussillon-Midi-Pyrénées',
        '83' => 'Provence-Alpes-Côte d\'Azur',
        '84' => 'Provence-Alpes-Côte d\'Azur',
        '85' => 'Pays de la Loire',
        '86' => 'Aquitaine-Limousin-Poitou-Charentes',
        '87' => 'Aquitaine-Limousin-Poitou-Charentes',
        '88' => 'Alsace-Champagne-Ardenne-Lorraine',
        '89' => 'Bourgogne-Franche-Comté',
        '90' => 'Bourgogne-Franche-Comté',
        '91' => 'Île-de-France',
        '92' => 'Île-de-France',
        '93' => 'Île-de-France',
        '94' => 'Île-de-France',
        '95' => 'Île-de-France',
        '2A' => 'Corse',
        '2B' => 'Corse',
        '971' => 'Guadeloupe',
        '973' => 'Guyane',
        '974' => 'La Réunion',
        '972' => 'Martinique',
        '976' => 'Mayotte'
    ];
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

    /**
     * Match all newcomer without a referral to a referral
     *
     * In this algorithme, we first try to match godsons to referral of the same branch
     * Then we try to match them we other branch if there is no space left.
     *
     * Current algorithme as 7 big steps :
     *
     * * STEP 1 : Creation of $counts and $counts2 arrays.
     * $counts is used in steps 2 and 3 to lower the number of queries to DB.
     * And $counts2 is used in steps X, X and X to lower the number of queries to DB.
     * The only difference between theses two arrays is that user are grouped by branch in the first one.
     *
     * * STEP 2 : Calculate the future number of godson for each referrals.
     * We get the number of newcomers and we add one to each referral until reaching their wanted maximum.
     * If there is still newcomers when all maximums are reached, we increment the maximum
     * (with $counts[branch]['+1']) for everyone except thoses with a maximum at 5 (6 godsons is really too much)
     * and we do it again until reaching the $MAXIMUM_OVERFLOW.
     *
     * * STEP 3 : Give godsons to referrals according to the calculated value in
     * precedent step and according to referral and godsons location.
     * For every newcomers, we take all referrals of his branch that have place left
     * and we try to match them by full postal_code and country. If there is a match
     * We associate them, if there is a multiple match, we take a random referral in the list
     * If there is no match we pass this newcomer and try with another one. At the
     * end of the newcomer list, we try agains with the newcomer that we have passed
     * with another matching solution. We do this again with all theses matching solution :
     *   * Full postal code
     *   * French departement code
     *   * French region
     *   * Country
     * If there is still no match, we try to match newcomers with everyone of his branch
     *
     * * STEP 4 : Remove PMOM and Master from left newcomers
     * We will now try to match newcomers with referral from other branches.
     * So we remove PMOM and masters because we don't want to associate them
     * with other branch referrals.
     *
     * * STEP 5 : Calculate the future number of godson for each referrals.
     * It's like the step 2 but we match newcomers with all branches

     * * STEP 6 : Give godsons to referrals according to the calculated value in
     * It's like the step 3 but we match newcomers with all branches
     *
     * * STEP 7 : If everyone has now a referral we save everything to DB
     *
     * Good luck if you want to edit this algo :)
     *
     */
    public static function matchReferrals()
    {
        // Settings you can tweak
        $MAXIMUM_OVERFLOW = 2; // Maximum of godson that can be added to a referral original max



        /***********************************************************************
         * STEP 1 : Creation of $counts and $counts2 arrays.
         **********************************************************************/

        // This array is created to minimize the number of query to db
        /*$counts = [
            'TC' => [
                42 => [ // 42 is a referral id
                    'current' => XX, // Current number of godson
                    'future' => XX, // Number of godson that will be associated at the end of the next step
                    'max' => XX, // Maximum number of godson asked by referral
                    '+1' => X, // Number of godson that can be added to `max` because there is not enough referrals. Used in step 2
                    '+1r2' => X, // Number of godson that can be added to `max` because there is not enough referrals. Used in step X
                    'student' => X, // Instance of the referral
                ]
                // referrals are deleted from the array once they reach the maximum
            ]
        ];
        $counts2 = [
            42 => [ // 42 is a referral id
                'current' => XX, // Current number of godson
                'future' => XX, // Number of godson that will be associated at the end of the next step
                'max' => XX, // Maximum number of godson asked by referral
                '+1' => X, // Number of godson that can be added to `max` because there is not enough referrals. Used in step 2
                '+1r2' => X, // Number of godson that can be added to `max` because there is not enough referrals. Used in step X
                'student' => X, // Instance of the referral
            ]
            // referrals are deleted from the array once they reach the maximum
        ];*/
        $counts = [];
        $counts2 = [];
        $referrals = Student::where(['referral' => 1, 'referral_validated' => 1])->get();
        foreach ($referrals as $referral) {
            if (!isset($counts[$referral->branch])) {
                $counts[$referral->branch] = [];
            }
            $counts[$referral->branch][$referral->student_id] = [
                'current' => $referral->newcomers->count(),
                'future' => $referral->newcomers->count(),
                'max' => $referral->referral_max,
                '+1' => 0,
                '+1r2' => 0,
                'student' => $referral, // You can comment this line to debug the first foreach
            ];

            // This array is used when there is no place left in some branches and we put godson from a branch to a referral from another
            if (($referral->branch != 'TC') // Remove TC2 because they are too young for branches
                    && $referral->branch != 'MM' // Remove PMOM because they doen't have godson from other branches
                    && $referral->branch != 'MP') { // Remove masters because they cannot have an engineer godson
                $counts2[$referral->student_id] = &$counts[$referral->branch][$referral->student_id];
            }
        }



        /***********************************************************************
         * STEP 2 : Calculate the future number of godson for each referrals
         **********************************************************************/

        foreach ($counts as $branch => $data) {
            // Get number of newcomers for this branch
            $newcomers = Newcomer::where(['branch' => $branch, 'referral_id' => null])->count();
            $currentGoal = 1;
            $overflow = 0; // Is true when we decide to add one to each maximum (but still < 5)
            while ($newcomers > 0 && $currentGoal <= 5) {
                // Randomize the foreach
                $student_ids = array_keys($data);
                shuffle($student_ids);
                foreach ($student_ids as $student_id) {
                    $referral_array = &$counts[$branch][$student_id];
                    if ($referral_array['future'] < $currentGoal
                        && $currentGoal <= $referral_array['max']+$referral_array['+1']
                        && $newcomers > 0) {
                        $transfer = $currentGoal - $referral_array['future'];
                        $referral_array['future'] += $transfer;
                        $newcomers -= $transfer;
                    }
                }
                $currentGoal++;
                // Check if need to go over maximums and add +1
                if ($currentGoal > 5 && $overflow < $MAXIMUM_OVERFLOW && $newcomers > 0) {
                    foreach ($data as $student_id => $tmp) {
                        if ($counts[$branch][$student_id]['max'] < 5) {
                            $counts[$branch][$student_id]['+1']++;
                        }
                    }
                    $overflow++;
                    $currentGoal = 1;
                }
            }

            // Debugging snippet to see repartition and set the $MAXIMUM_OVERFLOW var
            /*
            $future = 0;
            foreach ($data as $student_id => $tmp) {
                $future += $counts[$branch][$student_id]['future'];
            }
            echo '-----'.$branch.'-----'."\n";
            echo 'Nombre de nouveaux : '.(Newcomer::where(['branch' => $branch])->count())."\n";
            echo 'Nombre de nouveaux qui ont trouvé un parrain : '.$future . " (peut être superieur au nombre de nouveaux si des parrains de cette branche ont parrainé des nouveaux d'autres branches)\n";
            print_r($counts[$branch]);
            */
        }



        /***********************************************************************
         * STEP 3 : Give godsons to referrals according to the calculated value
         * in precedent step and according to referral and godsons location
         **********************************************************************/

        $round = 0;
        $allNewcomers = Newcomer::whereNull('referral_id')->get();
        $newcomers = [];
        foreach ($allNewcomers as $value) {
            $newcomers[] = $value;
        }
        $branchNotFound = []; // Newcomers in this array will have referral from other branches
        while (count($newcomers) > 0) {
            foreach ($newcomers as $key => $newcomer) {
                // Create array of users that are matching with the newcomer
                $matchingArray = [];
                if (isset($counts[$newcomer->branch])) {
                    foreach ($counts[$newcomer->branch] as $student_id => $array) {
                        if ($array['current'] >= $array['future']) {
                            unset($counts[$newcomer->branch][$student_id]);
                        } else {
                            switch ($round) {
                                case 0: // Match if newcomer and referral have same postal_code and country
                                    if ($newcomer->postal_code == $array['student']->postal_code
                                            && strtolower($newcomer->country) == strtolower($array['student']->country)) {
                                        $matchingArray[] = $student_id;
                                    }
                                    break;
                                case 1: // Match if newcomer and referral have same french departement (postal_code/1000) and country
                                    if (floor($newcomer->postal_code/1000) == floor($array['student']->postal_code/1000)
                                            && strtolower($newcomer->country) == strtolower($array['student']->country)) {
                                        $matchingArray[] = $student_id;
                                    }
                                    break;
                                case 2: // Match if newcomer and referral have same french region (region[postal_code/1000]) and country
                                    if (isset(self::REGION[floor($newcomer->postal_code/1000)]) && isset(self::REGION[floor($array['student']->postal_code/1000)])
                                            && self::REGION[floor($newcomer->postal_code/1000)] == self::REGION[floor($array['student']->postal_code/1000)]
                                            && strtolower($newcomer->country) == strtolower($array['student']->country)) {
                                        $matchingArray[] = $student_id;
                                    }
                                    break;
                                case 3: // Match if newcomer and referral have same country
                                    if (trim(strtolower($newcomer->country)) == trim(strtolower($array['student']->country))) {
                                        $matchingArray[] = $student_id;
                                    }
                                    break;
                                default: // Match if newcomer and referral have nothing in common
                                    $matchingArray[] = $student_id;
                            }
                        }
                    }

                    // Take a random student in the matched referral list
                    if (count($matchingArray)) {
                        $student_id = $matchingArray[array_rand($matchingArray)];
                        $newcomer->referral_id = $student_id;
                        $counts[$newcomer->branch][$student_id]['current']++;

                        // Debugging snippet
                        /*
                        $student = $counts[$newcomer->branch][$student_id]['student'];
                        echo $round.' '. $newcomer->first_name . ' '.$newcomer->last_name.' ('.$newcomer->branch.'|'.$newcomer->postal_code.','.$newcomer->country.') => ' .$student->first_name . ' '.$student->last_name.' ('.$student->branch.'|'.$student->postal_code.','.$student->country.')'
                            .' '.$counts[$newcomer->branch][$student_id]['current'].'/'.$counts[$newcomer->branch][$student_id]['future']."\n";
                        */

                        unset($newcomers[$key]);
                    }
                    // If nothing is found on round 4, it's time to put the coconut down NOW !
                    elseif ($round >= 4) {
                        $branchNotFound[] = $newcomers[$key];
                        unset($newcomers[$key]);
                    }
                } else {
                    $branchNotFound[] = $newcomers[$key];
                    unset($newcomers[$key]);
                }
            }
            $round++;
        }



        /***********************************************************************
         * STEP 4 : Remove PMOM and Master from left newcomers
         **********************************************************************/

        foreach ($branchNotFound as $key => $value) {
            if ($value->branch == 'MM' || $value->branch == 'MP') {
                unset($branchNotFound[$key]);
            }
        }



        /***********************************************************************
         * STEP 5 : Calculate the future number of godson for each referrals
         **********************************************************************/

        $newcomers = count($branchNotFound);
        $currentGoal = 1;
        $overflow = 0; // Is true when we decide to add one to each maximum (but still <= 5)
        while ($newcomers > 0 && $currentGoal <= 5) {
            // Randomize
            $student_ids = array_keys($counts2);
            shuffle($student_ids);
            foreach ($student_ids as $student_id) {
                // unset($counts2[$student_id]['student']); // Uncomment this to debug

                $referral_array = &$counts2[$student_id];
                if ($referral_array['future'] < $currentGoal
                    && $currentGoal <= $referral_array['max']+$referral_array['+1r2']
                    && $newcomers > 0) {
                    $transfer = $currentGoal - $referral_array['future'];
                    $referral_array['future'] += $transfer;
                    $newcomers -= $transfer;
                }
            }
            $currentGoal++;
            // Check if need to go over maximums and add +1
            if ($currentGoal > 5 && $overflow < $MAXIMUM_OVERFLOW && $newcomers > 0) {
                foreach ($counts2 as $student_id => $tmp) {
                    if ($counts2[$student_id]['max'] < 5) {
                        $counts2[$student_id]['+1r2']++;
                    }
                }
                $overflow++;
                $currentGoal = 1;
            }
        }



        /***********************************************************************
         * STEP 6 : Give godsons to referrals according to the calculated value
         * in precedent step and according to referral and godsons location
         **********************************************************************/

        $round = 0;
        $newcomers = $branchNotFound;
        while (count($newcomers) > 0) {
            foreach ($newcomers as $key => $newcomer) {
                // Create array of users that are matching with the newcomer
                $matchingArray = [];
                if (isset($counts2)) {
                    foreach ($counts2 as $student_id => $array) {
                        if ($array['current'] >= $array['future']) {
                            unset($counts2[$student_id]);
                        } else {
                            switch ($round) {
                                case 0: // Match if newcomer and referral have same postal_code and country
                                    if ($newcomer->postal_code == $array['student']->postal_code
                                            && strtolower($newcomer->country) == strtolower($array['student']->country)) {
                                        $matchingArray[] = $student_id;
                                    }
                                    break;
                                case 1: // Match if newcomer and referral have same french departement (postal_code/1000) and country
                                    if (floor($newcomer->postal_code/1000) == floor($array['student']->postal_code/1000)
                                            && strtolower($newcomer->country) == strtolower($array['student']->country)) {
                                        $matchingArray[] = $student_id;
                                    }
                                    break;
                                case 2: // Match if newcomer and referral have same french region (region[postal_code/1000]) and country
                                    if (isset(self::REGION[floor($newcomer->postal_code/1000)]) && isset(self::REGION[floor($array['student']->postal_code/1000)])
                                            && self::REGION[floor($newcomer->postal_code/1000)] == self::REGION[floor($array['student']->postal_code/1000)]
                                            && strtolower($newcomer->country) == strtolower($array['student']->country)) {
                                        $matchingArray[] = $student_id;
                                    }
                                    break;
                                case 3: // Match if newcomer and referral have same country
                                    if (trim(strtolower($newcomer->country)) == trim(strtolower($array['student']->country))) {
                                        $matchingArray[] = $student_id;
                                    }
                                    break;
                                default: // Match if newcomer and referral have nothing in common
                                    $matchingArray[] = $student_id;
                            }
                        }
                    }

                    // Take a random student in the matched referral list
                    if (count($matchingArray)) {
                        $student_id = $matchingArray[array_rand($matchingArray)];
                        $newcomer->referral_id = $student_id;
                        $counts2[$student_id]['current']++;

                        // Debugging code
                        /*
                        $student = $counts2[$student_id]['student'];
                        echo $round.' '. $newcomer->first_name . ' '.$newcomer->last_name.' ('.$newcomer->branch.'|'.$newcomer->postal_code.','.$newcomer->country.') => ' .$student->first_name . ' '.$student->last_name.' ('.$student->branch.'|'.$student->postal_code.','.$student->country.')'
                            .' '.$counts2[$student_id]['current'].'/'.$counts2[$student_id]['future']."\n";
                        */

                        unset($newcomers[$key]);
                    }
                    // If nothing is found on round 4, it's time to put the coconut down NOW !
                    elseif ($round >= 4) {
                        // Warning not all student have referral !!
                        $branchNotFound[] = $newcomers[$key];
                        unset($newcomers[$key]);
                    }
                } else {
                    // Warning not all student have referral !!
                    $branchNotFound[] = $newcomers[$key];
                    unset($newcomers[$key]);
                }
            }
            $round++;
        }



        /***********************************************************************
         * Debugging snippet that synthetise referral matching
         **********************************************************************/
        /*
        $referralList = [];
        foreach ($allNewcomers as $newcomer) {
            $referralList[$newcomer->referral_id][] = $newcomer;
        }
        foreach ($referralList as $referral_id => $array) {
            if (isset($array[0]->referral)) {
                echo '<h3>'.$array[0]->referral->first_name.' '.$array[0]->referral->last_name.'</h3>';

                echo '<p>max: '.$array[0]->referral->referral_max.', '.$array[0]->referral->branch.$array[0]->referral->level.', '.$array[0]->referral->postal_code.', '.$array[0]->referral->country.'</p><ul>';
            } else {
                echo '<h3>Aucun</h3><ul>';
            }
            foreach ($array as $godson) {
                echo '<li>'.$godson->first_name.' '.$godson->last_name.' : '.$godson->branch.', '.$godson->postal_code.', '.$godson->country.', ';
                if (isset($godson->referral)) {
                    if ($godson->postal_code == $godson->referral->postal_code
                                && strtolower($godson->country) == strtolower($godson->referral->country)) {
                        echo 'CP';
                    } elseif (floor($godson->postal_code/1000) == floor($godson->referral->postal_code/1000)
                                && strtolower($godson->country) == strtolower($godson->referral->country)) {
                        echo 'departement';
                    } elseif (isset(self::REGION[floor($godson->postal_code/1000)]) && isset(self::REGION[floor($godson->referral->postal_code/1000)])
                                && self::REGION[floor($godson->postal_code/1000)] == self::REGION[floor($godson->referral->postal_code/1000)]
                                && strtolower($godson->country) == strtolower($godson->referral->country)) {
                        echo 'region';
                    } elseif (trim(strtolower($godson->country)) == trim(strtolower($godson->referral->country))) {
                        echo 'pays';
                    }
                }
                echo '</li>';
            }
            echo '</ul>';
        }
        */

        /***********************************************************************
         * STEP 7 : If everyone has now a referral we save everything to DB
         **********************************************************************/

        // If we cannot match everyone, we cancel everything
        if (count($newcomers) > 0) {
            return false;
        }

        // Save it to DB
        foreach ($allNewcomers as $newcomer) {
            $newcomer->save();
        }
        return true;
    }
}
