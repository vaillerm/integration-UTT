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
    ];
}
