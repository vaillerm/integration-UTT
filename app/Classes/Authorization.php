<?php

namespace App\Classes;

use App\Models\Team;
use App\Models\Student;
use Config;
use Auth;

/**
 * Authorization helper
 */
class Authorization
{

    /**
     * The current DateTime
     *
     * @var \DateTime
     */
    protected $now;
    protected $tmp;

    /**
     * The current DateTime
     *
     * @return \DateTime
     */
    public function now()
    {
        if (!$this->now) {
            $this->now = new \DateTime();
        }
        return $this->now;
    }

    /**
     * Check if current user is authorized against a group and a facultative action
     * @param  string  $group  Group of user : admin, ce, orga, referral, student
     * @param  string  $action  Specific action for this group of user
     *
     * @return bool
     */
    public function can($group, $action = '')
    {
        if ($group == 'newcomer') {
            // Check if newcomer is identified
            if (!Auth::check() || (Auth::user()->isStudent())) {
                return false;
            }

            // Action verification
            $count = Student::where('is_newcomer', 1)->where('wei', 1)->count();
            switch ($action) {
                case 'wei':
                    if (!Auth::user()->wei
                        && ($this->now() <= new \DateTime(Config::get('services.wei.registrationStart'))
                        || $this->now() >= new \DateTime(Config::get('services.wei.registrationEnd'))
                        || $count >= Config::get('services.wei.newcomerMax'))) {
                        return false;
                    }
                    break;
            }
        } else {
            // Login/student verification
            if (in_array($group, ['student', 'admin', 'orga', 'ce', 'referral', 'volunteer', 'moderator'])
                    && !\EtuUTT::isAuth()) {
                return false;
            }
            $student = \EtuUTT::student();

            // Volunteer verification
            if (in_array($group, ['admin', 'orga', 'ce', 'volunteer', 'moderator'])
                    && !\EtuUTT::student()->volunteer) {
                return false;
            }

            // Group verification
            if ($group == 'admin'
                    && !$student->isAdmin()) {
                return false;
            }
            // Group verification
            if ($group == 'moderator'
                    && !$student->isAdmin()
                    && !$student->isModerator()) {
                return false;
            }
            // Group verification
            if ($group == 'referral'
                    && !$student->referral) {
                return false;
            } elseif ($group == 'orga'
                    && !$student->orga) {
                return false;
            } elseif ($group == 'ce'
                    && !$student->ce) {
                return false;
            }
            $teamCount = Team::count();
            // Action verification
            if ($group == 'ce') {
                switch ($action) {
                    case 'inTeam':
                        if (!$student->team) {
                            return false;
                        }
                        break;
                    case 'respo':
                        if (!$student->team || $student->team->respo_id != $student->id) {
                            return false;
                        }
                        break;
                    case 'create':
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
                        if ($this->now() > new \DateTime(Config::get('services.ce.deadline'))
                            || $this->now() < new \DateTime(Config::get('services.ce.opening'))
                            || $student->team) {
                            return false;
                        }
                        if($student->branch == "TC" && $student->level < 4){
                            if($countTC >= Config::get('services.ce.maxteamtc')){
                                info("counttc>max");
                                return false;
                            }
                        }
                        else
                        {
                            if($countBranch >= Config::get('services.ce.maxteambranch')){
                                info($countBranch . " countbranch>max");
                                return false;
                            }
                        }
                        break;
                    case 'edit':
                        if (!$student->team || $student->team->respo_id != $student->id
                            || $this->now() < new \DateTime(Config::get('services.ce.opening'))
                            || $this->now() > new \DateTime(Config::get('services.ce.deadline'))) {
                            return false;
                        }
                        break;
                    case 'editName':
                        if (!$student->team || $student->team->respo_id != $student->id
                            || $this->now() < new \DateTime(Config::get('services.ce.opening'))
                            || $this->now() > new \DateTime(Config::get('services.ce.deadline'))
                            || $this->now() < new \DateTime(Config::get('services.ce.teamnamedeadline'))) {
                            return false;
                        }
                        break;
                    case 'join':
                        if (!$student->team
                            || $this->now() < new \DateTime(Config::get('services.ce.opening'))
                            || $this->now() > new \DateTime(Config::get('services.ce.deadline'))) {
                            return false;
                        }
                        break;
                }
            } elseif ($group == 'student') {
                switch ($action) {
                    case 'inTeam':
                        if (!$student->team) {
                            return false;
                        }
                    break;
                    case 'referral':
                        if ($this->now() > new \DateTime(Config::get('services.referral.deadline'))
                            || $this->now() < new \DateTime(Config::get('services.referral.opening'))
                            || $student->referral_validated
                            || $student->referral) {
                            return false;
                        }
                        break;
                    case 'ce':
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
                        if ($this->now() > new \DateTime(Config::get('services.ce.deadline'))
                            || $this->now() < new \DateTime(Config::get('services.ce.opening'))
                            || $student->ce) {
                            return false;
                        }
                        if($student->branch == "TC" && $student->level < 4){
                            if($countTC >= Config::get('services.ce.maxteamtc')){
                                return false;
                            }
                        }
                        else
                        {
                            if($countBranch >= Config::get('services.ce.maxteambranch')){
                                return false;
                            }
                        }
                        break;
                }
            } elseif ($group == 'referral') {
                switch ($action) {
                    case 'edit':
                        if ($this->now() > new \DateTime(Config::get('services.referral.deadline'))
                            || $this->now() < new \DateTime(Config::get('services.referral.opening'))
                            || $student->referral_validated) {
                            return false;
                        }
                        break;
                }
            }
        }
        return true;
    }

    /**
     * Return a countdown array for the given deadline
     * @param  string  $group  Group of user : admin, ce, orga, referral, student
     * @param  string  $action  Specific action for this group of user
     *
     * @return array
     */
    public function countdown($group, $action)
    {
        $now = new \DateTime();
        $date = null;

        // Action verification
        if ($group == 'ce') {
            switch ($action) {
                case 'create':
                case 'edit':
                    $teams = Team::all();
                    $countTC = 0;
                    $countBranch = 0;
                    foreach ($teams as $t) {
                        if($t->respo->branch == "TC" && $t->respo->level < 4){
                            $countTC ++;
                        }
                        else{
                            $countBranch++;
                        }
                    }
                    
                    if ($this->now() < new \DateTime(Config::get('services.ce.opening'))) {
                        $date = new \DateTime(Config::get('services.ce.opening'));
                    }
                    elseif ($this->now() < new \DateTime(Config::get('services.ce.deadline'))) {
                        $date = new \DateTime(Config::get('services.ce.fakeDeadline'));
                    }
                    break;
                case 'editName':                    
                    $date = new \DateTime(Config::get('services.ce.teamnamedeadline'));
                    break;
            }
        } elseif ($group == 'student') {
            switch ($action) {
                case 'referral':
                    if ($this->now() < new \DateTime(Config::get('services.referral.opening'))) {
                        $date = new \DateTime(Config::get('services.referral.opening'));
                    }
                    elseif ($this->now() < new \DateTime(Config::get('services.referral.deadline'))) {
                        $date = new \DateTime(Config::get('services.referral.fakeDeadline'));
                    }
                    break;
            }
        } elseif ($group == 'referral') {
            switch ($action) {
                case 'edit':
                    if ($this->now() < new \DateTime(Config::get('services.referral.opening'))) {
                        $date = new \DateTime(Config::get('services.referral.opening'));
                    }
                    elseif ($this->now() < new \DateTime(Config::get('services.referral.deadline'))) {
                        $date = new \DateTime(Config::get('services.referral.fakeDeadline'));
                    }
                    break;
            }
        }

        if($date) {
            return (new \DateTime())->diff($date);
        }
        return null;
    }
}
