<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Crypt;

class Newcomer extends Model
{

    public $table = 'newcomers';
    public $timestamps = true;

    public $primaryKey = 'id';

    public function getDates()
    {
        return ['created_at', 'update_at', 'birth'];
    }

    public $fillable = [
        'first_name',
        'last_name',
        'sex',
        'birth',
        'email',
        'phone',
        'branch',
        'registration_email',
        'registration_cellphone',
        'registration_phone',
        'postal_code',
        'country',
        'ine',
        'referral_id',
        'team_id',
    ];

    /**
     * Define the One-to-One relation with Team.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function team()
    {
        return $this->belongsTo('App\Models\Team');
    }

    /**
     * Define the One-to-One relation with Student.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function referral()
    {
        return $this->belongsTo('App\Models\Student');
    }

    /**
     * @return string
     */
    public function fullName()
    {
        return $this->first_name . ' ' . $this->last_name;
    }

    public static function boot()
    {
        parent::boot();

        Newcomer::creating(function ($newcomer) {
            if (empty($newcomer->password)) {
                $newcomer->password = Crypt::encrypt(self::generatePassword());
            }
            if (empty($newcomer->login)) {
                $login = strtolower(mb_substr(mb_substr(preg_replace("/[^A-Za-z0-9]/", '', $newcomer->first_name), 0, 1) . preg_replace("/[^A-Za-z0-9]/", '', $newcomer->last_name), 0, 8));
                $i = '';
                while (Newcomer::where(['login' => $login . $i])->count()) {
                    if (empty($i)) {
                        $i = 1;
                    }
                    $i++;
                }
                $newcomer->login = $login . $i;
            }
        });
    }

    /**
     * Generate a rememberable password
     * @return string password
     */
    public static function generatePassword()
    {
        $consonant = 'bcdfgjklmnpqrstvwxz';
        $vowel = 'aeiou';
        $countC = mb_strlen($consonant);
        $countV = mb_strlen($vowel);
        $result = '';

        for ($i = 0, $result = ''; $i < 4; $i++) {
            $index = mt_rand(0, $countC - 1);
            $result .= mb_substr($consonant, $index, 1);

            $index = mt_rand(0, $countV - 1);
            $result .= mb_substr($vowel, $index, 1);
        }

        return $result;
    }
}
