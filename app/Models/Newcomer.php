<?php

namespace App\Models;

use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;
use Crypt;

class Newcomer extends Model implements Authenticatable
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
        'parent_name',
        'parent_phone',
        'medical_allergies',
        'medical_treatment',
        'medical_note',
    ];


    const CHECKLIST_DEFINITION = [
        'profil_email' => [
            'action' => 'Compléter ton email',
            'page' => 'profil',
        ],
        'profil_phone' => [
            'action' => 'Compléter ton numéro de téléphone',
            'page' => 'profil',
        ],
        'profil_parent_name' => [
            'action' => 'Compléter le nom de ton contact d\'urgence',
            'page' => 'profil',
        ],
        'profil_parent_phone' => [
            'action' => 'Compléter le numéro de ton contact d\'urgence',
            'page' => 'profil',
        ],
        'referral' => [
            'action' => 'Prendre contact avec ton parrain',
            'page' => 'referral',
        ],
    ];


    public $checklistArray = [];

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

    public function isChecked($element)
    {
        return (!empty($this->getChecklist()[$element]));
    }

    public function isPageChecked($page)
    {
        foreach ($this->getChecklist() as $key => $value) {
            if (self::CHECKLIST_DEFINITION[$key]['page'] == $page && !$value) {
                return false;
            }
        }
        return true;
    }

    public function setCheck($element, bool $bool = true)
    {
        $checklist = $this->getChecklist();
        $checklist[$element] = $bool;
        $this->setChecklist($checklist);
    }

    public function getChecklistPercent()
    {
        $count = 0;
        foreach ($this->getChecklist() as $value) {
            if (!empty($value)) {
                $count++;
            }
        }
        return floor(($count/count($this->getChecklist()))*100);
    }

    public function getNextCheck()
    {
        $count = 0;
        foreach ($this->getChecklist() as $key => $value) {
            if (empty($value)) {
                return self::CHECKLIST_DEFINITION[$key];
            }
        }
        return [
            'page' => 'done',
            'action' => 'Aucune !'
        ];
    }

    /**
     * Get the checklist array
     *
     * @return string
     */
    public function getChecklist()
    {
        if (empty($this->checklistArray)) {
            $array = unserialize($this->checklist);
            if (empty($array)) {
                $array = [];
            }
            $definition = array_fill_keys(array_keys(self::CHECKLIST_DEFINITION), false);
            $this->checklistArray = array_merge($definition, $array);
        };
        return $this->checklistArray;
    }

    /**
     * Set the checklist array
     *
     * @return string
     */
    public function setChecklist(array $checklist)
    {
        $this->checklistArray = $checklist;
        $this->checklist = serialize($this->checklistArray);
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

    /**
     * Get the name of the unique identifier for the user.
     *
     * @return string
     */
    public function getAuthIdentifierName()
    {
        return 'id';
    }

    /**
     * Get the unique identifier for the user.
     *
     * @return mixed
     */
    public function getAuthIdentifier()
    {
        $name = $this->getAuthIdentifierName();

        return $this->attributes[$name];
    }

    /**
     * Get the password for the user.
     *
     * @return string
     */
    public function getAuthPassword()
    {
        return $this->attributes['password'];
    }

    /**
     * Get the "remember me" token value.
     *
     * @return string
     */
    public function getRememberToken()
    {
        return $this->attributes[$this->getRememberTokenName()];
    }

    /**
     * Set the "remember me" token value.
     *
     * @param  string  $value
     * @return void
     */
    public function setRememberToken($value)
    {
        $this->attributes[$this->getRememberTokenName()] = $value;
    }

    /**
     * Get the column name for the "remember me" token.
     *
     * @return string
     */
    public function getRememberTokenName()
    {
        return 'remember_token';
    }
}
