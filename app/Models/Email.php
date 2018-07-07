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
    const WEI_SUSCBRIBED  = 18;

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
        self::WEI_SUSCBRIBED => 'Toutes les personnes inscrites au WEI et ayant un numéro de bus',
    ];

    /**
     * @param $lists Tableau ou numéro de listes à utiliser
     * @param bool $publicity Permet de filtrer les gens pour ou contre la publicité
     * @param MailRevision|null $mail_rev Instance du mail a envoyé
     * @param bool $unique S'assure qu'une personne ne recevra pas deux fois le même mail
     * @return array
     */
    public static function mailFromLists($lists, $publicity = true, MailRevision $mail_rev =null, $unique = false)
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
                    $students = User::student()->with(['mailHistories', 'team'])->where('volunteer', 1)->get();
                    break;
                case Email::CE_VALIDATED:
                    $students = User::student()->with(['mailHistories', 'team'])->where('ce', 1)->whereNotNull('team_id')->where('team_accepted', 1)->get();
                    break;
                case Email::REFERRALS_VALIDATED:
                    $students = User::student()->with(['mailHistories', 'team'])->where('referral', 1)->where('referral_validated', 1)->get();
                    break;
                case Email::REFERRALS_INCOMPLETE:
                    $students = User::student()->with(['mailHistories', 'team'])->where('referral', 1)
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
                    $students = User::student()->with(['mailHistories', 'team'])->where('referral', 1)->where('referral_validated', 1)->where('branch', '<>', 'tc')->get();
                    break;
                case Email::REFERRALS_VALIDATED_TC:
                    $students = User::student()->with(['mailHistories', 'team'])->where('referral', 1)->where('referral_validated', 1)->where('branch', '=', 'tc')->get();
                    break;
                case Email::ORGA:
                    $students = User::student()->with(['mailHistories', 'team'])->where('orga', 1)->get();
                    break;
                case Email::ADMIN:
                    $students = User::student()->with(['mailHistories', 'team'])->where('admin', 100)->get();
                    break;
                case Email::NEWCOMERS_ALL:
                    $students = User::newcomer()->with(['mailHistories', 'team', 'godFather'])->get();
                    break;
                case Email::NEWCOMERS_ALL_TC:
                    $students = User::newcomer()->with(['mailHistories', 'team', 'godFather'])->where('branch', 'TC')->get();
                    break;
                case Email::NEWCOMERS_ALL_BRANCH:
                    $students = User::newcomer()->with(['mailHistories', 'team', 'godFather'])->where('branch', '<>', 'TC')->where('branch', '<>', 'MP')->get();
                    break;
                case Email::NEWCOMERS_ALL_MASTER:
                    $students = User::newcomer()->with(['mailHistories', 'team', 'godFather'])->where('branch', 'MP')->get();
                    break;
                case Email::NEWCOMERS_FILLED:
                    $students = User::newcomer()->with(['mailHistories', 'team', 'godFather'])->where('email', '<>', '')->whereNotNull('email')->get();
                    break;
                case Email::NEWCOMERS_FILLED_TC:
                    $students = User::newcomer()->with(['mailHistories', 'team', 'godFather'])->where('branch', 'TC')->where('email', '<>', '')->whereNotNull('email')->get();
                    break;
                case Email::NEWCOMERS_FILLED_BRANCH:
                    $students = User::newcomer()->with(['mailHistories', 'team', 'godFather'])->where('branch', '<>', 'TC')->where('branch', '<>', 'MP')->where('email', '<>', '')->whereNotNull('email')->get();
                    break;
                case Email::NEWCOMERS_FILLED_MASTER:
                    $students = User::newcomer()->with(['mailHistories', 'team', 'godFather'])->where('branch', 'MP')->where('email', '<>', '')->whereNotNull('email')->get();
                    break;
                case Email::NEWCOMERS_ALL_WITH_GODFATHER_AND_TEAM:
                    $students = User::newcomer()->with(['mailHistories', 'team', 'godFather'])->whereNotNull('team_id')->whereNotNull('referral_id')->get();
                    break;
                case Email::WEI_SUSCBRIBED:
                    $students = User::with(['mailHistories', 'team', 'godFather'])->where('wei', 1)->where('bus_id','>', 0)->get();
                    break;
                default:
                    echo 'Error : Unknown email list id';
                    break;
            }

            if($students) {
                foreach ($students as $student) {
                    if (($publicity && $student->allow_publicity) || !$publicity) {
                        if(!(($unique && $mail_rev) && $student->mailHistories->where('mail_revision_id', $mail_rev->id)->count()>0)) {
                            $student_mail = ($student->registration_email ? $student->registration_email : $student->email);
                            $mails[$student_mail] = ['name' => $student->first_name . ' ' . $student->last_name, 'user' => $student];
                        }
                    }
                }
            }

        }

        return $mails;
    }
}
