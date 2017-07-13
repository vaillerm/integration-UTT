<?php

namespace App\Models;

use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;
use Crypt;
use Config;

class Student extends Model implements Authenticatable
{
    public $table = 'students';
    public $timestamps = true;

    const SEX_MALE = 0;
    const SEX_FEMALE = 1;

    const ADMIN_NOT = 0;
    const ADMIN_MODERATOR = 50;
    const ADMIN_FULL = 100;

    public $primaryKey = 'id';

    public $fillable = [
        'student_id',
        'first_name',
        'last_name',
        'sex',
        'birth',
        'surname',
        'email',
        'phone',
        'postal_code',
        'city',
        'country',
        'branch',
        'level',
        'referral_text',
        'referral_max',
        'volunteer',
        'facebook',
        'team_id',
        'ce',
        'registration_email',
        'registration_cellphone',
        'registration_phone',
        'ine',
        'referral_id',
        'parent_name',
        'parent_phone',
        'medical_allergies',
        'medical_treatment',
        'medical_note',
        'is_newcomer'
    ];

    public $hidden = [
        'created_at',
        'updated_at',
    ];

    public $checklistArray = [];

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
        'team_disguise' => [
            'action' => 'Rejoindre le groupe facebook de ton équipe et faire ton déguisement ',
            'page' => 'team',
        ],
        'wei_pay' => [
            'action' => 'T\'inscrire pour le Week-End d\'Intégration',
            'page' => 'wei',
        ],
        'wei_guarantee' => [
            'action' => 'Déposer la caution',
            'page' => 'wei',
        ],
        'wei_authorization' => [
            'action' => 'Déposer l\'autorisation parentale',
            'page' => 'wei',
        ],
    ];

    /**
     * Accessors mail
     */

    public function getEmailAttribute($value)
    {
        if(!$value)
            return $this->registration_email;
        else return $value;
    }
    /**
     * Scope a query to only include students that are newcomers.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeNewcomer($query)
    {
        return $query->where('is_newcomer', true);
    }

    /**
     * Scope a query to only include students that are students.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeStudent($query)
    {
        return $query->where('is_newcomer', false)->whereNotNull('student_id');
    }

    /**
     * Query referrals newscomers
     */
    public function newcomers()
    {
        return $this->hasMany(Student::class, 'student_id', 'referral_id');
    }

    public function isStudent()
    {
        return !($this->is_newcomer);
    }

    public function isNewcomer()
    {
        return !($this->isStudent());
    }
    /**
     * Return newcomers referal
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function godFather()
    {
        return $this->belongsTo(Student::class, 'referral_id', 'student_id')->where('referral', true);
    }

    public function getDates()
    {
        return ['created_at', 'updated_at', 'last_login', 'birth'];
    }

    /**
     * Define the One-to-Many relation with Team;
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function team()
    {
        return $this->belongsTo(Team::class, 'team_id', 'id');
    }

    /**
     * Test if the student can all of the dashboard
     * @return bool
     */
    public function isAdmin()
    {
        return ($this->admin == Student::ADMIN_FULL);
    }

    /**
     * Test if the student can all of the dashboard
     * @return bool
     */
    public function isModerator()
    {
        return ($this->admin == Student::ADMIN_MODERATOR);
    }

    /**
     * Define the One-to-One relation with Payment
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function weiPayment()
    {
        return $this->belongsTo('App\Models\Payment', 'wei_payment');
    }

    /**
     * Define the One-to-One relation with Payment
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function sandwichPayment()
    {
        return $this->belongsTo('App\Models\Payment', 'sandwich_payment');
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
     * Retourne le secret d'authentification
     */

    public function getHashAuthentification()
    {
        return sha1($this->registration_email.$this->created_at->timestamp);
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
     * Get the "remember me" token value.
     *
     * @return string
     */
    public function getRememberToken()
    {
        return $this->attributes[$this->getRememberTokenName()];
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
     * Define the One-to-One relation with Payment
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function guaranteePayment()
    {
        return $this->belongsTo('App\Models\Payment', 'guarantee_payment');
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

        Student::creating(function ($newcomer) {
            if (empty($newcomer->password)) {
                $newcomer->password = Crypt::encrypt(self::generatePassword());
            }
            if (empty($newcomer->login)) {
                $login = strtolower(mb_substr(mb_substr(preg_replace("/[^A-Za-z0-9]/", '', $newcomer->first_name), 0, 1) . preg_replace("/[^A-Za-z0-9]/", '', $newcomer->last_name), 0, 8));
                $i = '';
                while (Student::where(['login' => $login . $i])->count()) {
                    if (empty($i)) {
                        $i = 1;
                    }
                    $i++;
                }
                $newcomer->login = $login . $i;
            }
        });
    }

    public function updateWei()
    {
        $weiPayment = $this->weiPayment && in_array($this->weiPayment->state, ['paid', 'returned']);
        $guaranteePayment = $this->guaranteePayment && in_array($this->guaranteePayment->state, ['paid', 'returned']);

        // only if it's a newcomer
        if ($this->is_newcomer) {
            $this->setCheck('wei_pay', $weiPayment);
            $this->setCheck('wei_guarantee', $guaranteePayment);

            if ($this->birth->add(new \DateInterval('P18Y')) < (new \DateTime(Config::get('services.wei.start')))) {
                $this->setCheck('wei_authorization', true);
                $this->parent_authorization = true;
            } elseif ($this->parent_authorization) {
                $this->setCheck('wei_authorization', true);
            } else {
                $this->setCheck('wei_authorization', false);
            }
        }

        $wei = ($weiPayment || $guaranteePayment);
        if ($this->wei != $wei) {
            $this->wei = $wei;
        }
        $this->save();
    }
}
