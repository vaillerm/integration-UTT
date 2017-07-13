<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Email extends Model
{
    public $timestamps = true;

    // Emails lists
    // To add more email list edit this file and `app/Console/Commands/PutScheduledEmailToQueue.php`
    const STUPRELISTE                  = 0;
    const VOLUNTEERS                   = 1;
    const CE_VALIDATED                 = 2;
    const REFERRALS_VALIDATED          = 3;
    const REFERRALS_INCOMPLETE         = 4;
    const REFERRALS_VALIDATED_BRANCH   = 5;
    const REFERRALS_VALIDATED_TC       = 6;
    const ORGA                         = 7;
    const ADMIN                        = 8;
    const NEWCOMERS_ALL                = 9;
    const NEWCOMERS_ALL_TC             = 10;
    const NEWCOMERS_ALL_BRANCH         = 11;
    const NEWCOMERS_ALL_MASTER         = 12;
    const NEWCOMERS_FILLED             = 13;
    const NEWCOMERS_FILLED_TC          = 14;
    const NEWCOMERS_FILLED_BRANCH      = 15;
    const NEWCOMERS_FILLED_MASTER      = 16;
    const NEWCOMERS_ALL_WITH_GODFATHER_AND_TEAM  = 17;

    public static $listToFrench = [
        self::STUPRELISTE => 'Bénévoles et personnes inscrites sur la stupre-liste',
        self::VOLUNTEERS => 'Bénévoles',
        self::CE_VALIDATED => 'CE Validés',
        self::REFERRALS_VALIDATED => 'Parrains validés',
        self::REFERRALS_INCOMPLETE => 'Parrains incomplets',
        self::REFERRALS_VALIDATED_BRANCH => 'Parrains validés de branche',
        self::REFERRALS_VALIDATED_TC => 'Parrains validés de TC',
        self::ORGA => 'Orgas',
        self::NEWCOMERS_ALL => 'Tous les nouveaux (même ceux qui n\'ont pas entré leur email)',
        self::NEWCOMERS_ALL_TC => 'Nouveaux TC (même ceux qui n\'ont pas entré leur email)',
        self::NEWCOMERS_ALL_BRANCH => 'Nouveaux Branche (même ceux qui n\'ont pas entré leur email)',
        self::NEWCOMERS_ALL_MASTER => 'Nouveaux Master (même ceux qui n\'ont pas entré leur email)',
        self::NEWCOMERS_FILLED => 'Nouveaux qui ont entré leur email',
        self::NEWCOMERS_FILLED_TC => 'Nouveaux TC qui ont entré leur email',
        self::NEWCOMERS_FILLED_BRANCH => 'Nouveaux Branche qui ont entré leur email',
        self::NEWCOMERS_FILLED_MASTER => 'Nouveaux Master  qui ont entré leur email',
        self::NEWCOMERS_ALL_WITH_GODFATHER_AND_TEAM => 'Tous les nouveaux ayant un parrain et une équipe',
    ];

    public static function mailFromLists($lists, $publicity = true)
    {
        if(!is_array($lists))
            $lists = [$lists];

        $mails = [];

        foreach ($lists as $list)
        {
            $students = null;

            switch ($list) {
                case Email::STUPRELISTE:
                    $mails['stupre-liste@utt.fr'] = [ 'name' => 'STUPRE-liste', 'user' => null ];
                    break;
                case Email::VOLUNTEERS:
                    $students = Student::student()->where('volunteer', 1)->get();
                    break;
                case Email::CE_VALIDATED:
                    $students = Student::student()->where('ce', 1)->whereNotNull('team_id')->where('team_accepted', 1)->get();
                    break;
                case Email::REFERRALS_VALIDATED:
                    $students = Student::student()->where('referral', 1)->where('referral_validated', 1)->get();
                    break;
                case Email::REFERRALS_INCOMPLETE:
                    $students = Student::student()->where('referral', 1)
                        ->where('referral_validated', 0)
                        ->where(function ($query) {
                            $query->where('phone', '')
                                ->orWhereNull('phone')
                                ->orWhere('email', '')
                                ->orWhereNull('email')
                                ->orWhere('referral_text', '')
                                ->orWhereNull('referral_text');
                        })
                        ->get();
                    break;
                case Email::REFERRALS_VALIDATED_BRANCH:
                    $students = Student::student()->where('referral', 1)->where('referral_validated', 1)->where('branch', '<>', 'tc')->get();
                    break;
                case Email::REFERRALS_VALIDATED_TC:
                    $students = Student::student()->where('referral', 1)->where('referral_validated', 1)->where('branch', '=', 'tc')->get();
                    break;
                case Email::ORGA:
                    $students = Student::student()->where('orga', 1)->get();
                    break;
                case Email::ADMIN:
                    $students = Student::student()->where('admin', 100)->get();
                    break;
                case Email::NEWCOMERS_ALL:
                    $students = Student::newcomer()->get();
                    break;
                case Email::NEWCOMERS_ALL_TC:
                    $students = Student::newcomer()->where('branch', 'TC')->get();
                    break;
                case Email::NEWCOMERS_ALL_BRANCH:
                    $students = Student::newcomer()->where('branch', '<>', 'TC')->where('branch', '<>', 'MP')->get();
                    break;
                case Email::NEWCOMERS_ALL_MASTER:
                    $students = Student::newcomer()->where('branch', 'MP')->get();
                    break;
                case Email::NEWCOMERS_FILLED:
                    $students = Student::newcomer()->where('email', '<>', '')->whereNotNull('email')->get();
                    break;
                case Email::NEWCOMERS_FILLED_TC:
                    $students = Student::newcomer()->where('branch', 'TC')->where('email', '<>', '')->whereNotNull('email')->get();
                    break;
                case Email::NEWCOMERS_FILLED_BRANCH:
                    $students = Student::newcomer()->where('branch', '<>', 'TC')->where('branch', '<>', 'MP')->where('email', '<>', '')->whereNotNull('email')->get();
                    break;
                case Email::NEWCOMERS_FILLED_MASTER:
                    $students = Student::newcomer()->where('branch', 'MP')->where('email', '<>', '')->whereNotNull('email')->get();
                    break;
                case Email::NEWCOMERS_ALL_WITH_GODFATHER_AND_TEAM:
                    $students = Student::newcomer()->whereNotNull('team_id')->whereNotNull('referral_id')->get();
                    break;
                default:
                    echo 'Error : Unknown email list id';
                    break;
            }

            if($students) {
                foreach ($students as $student) {
                    if (($publicity && $student->allow_publicity) || !$publicity) {
                        $student_mail = ($student->registration_email ? $student->registration_email : $student->email);
                        $mails[$student_mail] = ['name' => $student->first_name . ' ' . $student->last_name, 'user' => $student];
                    }
                }
            }

            return $mails;
        }


    }
}
