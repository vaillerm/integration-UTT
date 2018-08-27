<?php

namespace App\Models;

use Laravel\Passport\HasApiTokens;
use Hash;
use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;
use Crypt;
use Config;
use Ramsey\Uuid\Uuid;

class User extends Model implements Authenticatable
{
    use HasApiTokens, Notifiable;

    public $table = 'users';
    public $timestamps = true;

    const SEX_MALE = 0;
    const SEX_FEMALE = 1;

    const ADMIN_NOT = 0;
    const ADMIN_MODERATOR = 50;
    const ADMIN_FULL = 100;

    public $primaryKey = 'id';

    protected $attributes = [
        'volunteer_preferences' => '[]',
        'remember_token' => '',
    ];

    public $fillable = [
        'student_id',
        'admitted_id',
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
        'registration_phone',
        'referral_id',
        'parent_name',
        'parent_phone',
        'medical_allergies',
        'medical_treatment',
        'medical_note',
        'is_newcomer',
        'device_token',
        'latitude',
        'longitude',
        'bus_id',
        'wei_majority',
        'mission_order',
        'mission_respo',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'volunteer_preferences' => 'array',
    ];

    public $hidden = [
        'created_at',
        'updated_at',
    ];

    public $dates = [
        'created_at',
        'updated_at',
        'birth'
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
        'back_to_school' => [
            'action' => 'Pause partenaires !',
            'page' => 'backtoschool',
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

    const VOLUNTEER_PREFERENCES = [
        'ce' => [
            'title' => 'Chef d\'équipe',
            'description' => 'Animer et accompagner une équipe de 25-30 nouveaux pendant toute l\'intégration.',
        ],
        'logistique' => [
            'title' => 'Logistique',
            'description' => 'Jouer avec des transpalettes, rassembler et acheminer tout le matériel nécessaire pour chaque activité de l\'intégration.',
        ],
        'communication' => [
            'title' => 'Communication',
            'description' => 'Animer nos différents réseaux sociaux pour teaser les nouveaux jusqu\'au bout.',
        ],
        'graphisme' => [
            'title' => 'Graphisme',
            'description' => 'Si tu aimes t\'amuser sur photoshop, l\'inté a besoin de toi pour de nombreux éléments : couverture du Gubu, t-shirt, supports de communication, etc.',
        ],
        'bouffe' => [
            'title' => 'Bouffe',
            'description' => 'Manger c\'est sacré ! Il nous faut des cuisto\' pour préparer les repas avec amour pour tous ces ventres affamés.',
        ],
        'deco' => [
            'title' => 'Déco',
            'description' => 'Créer une déco stylée pour habiller l\'UTT sur le thème de l\'intégration.',
        ],
        'animation' => [
            'title' => 'Animation',
            'description' => 'Pourquoi on est là ?!',
        ],
        'partenariat' => [
            'title' => 'Partenariat',
            'description' => 'Aller voir des entreprises pour récupérer des goodies, des réductions pour les étudiants ou de l\'argent pour l\'inté.',
        ],
        'soiree' => [
            'title' => 'Soirée d’intégration',
            'description' => 'Organiser une petite soirée de 1000 personnes dans l\'UTT.',
        ],
        'rallye' => [
            'title' => 'Rallye',
            'description' => 'Prévoir les différentes activités qui occuperont les 30 équipes pendant l\'après-midi du jeudi.',
        ],
        'village-asso' => [
            'title' => 'Village asso',
            'description' => 'Communiquer avec les assos de l\'UTT pour organiser une aprem de découverte des assos, sans que ce soit chiant pour les nouveaux.',
        ],
        'wei' => [
            'title' => 'WEI',
            'description' => 'Prévoir les différentes animations du weekend',
        ],
        'defi' => [
            'title' => 'Défi TC',
            'description' => 'Préparer l\'aprèm où les nouveaux TC devront faire preuve d\'ingéniosité pour marquer des points',
        ],
        'co-utt' => [
            'title' => 'Course d\'orientation TC',
            'description' => 'Organiser la course d\'orientation de découverte de l\'UTT, où les nouveaux pourront visiter des parties inaccessible de l’UTT.',
        ],
        'faux-test' => [
            'title' => 'Faux test',
            'description' => 'Organiser ce magnifique troll et faire un best-of des conneries qu\'ils auront écrits.',
        ],
        'gubu' => [
            'title' => 'GUBU',
            'description' => 'Mettre à jour le GUBU avec les nouveaux bails.',
        ],
        'co-wei' => [
            'title' => 'Course d\'orientation WEI',
            'description' => 'Organiser les activités qui accueilleront les nouveaux sur le lieu du WEI.',
        ],
        'securite' => [
            'title' => 'Securité',
            'description' => 'Jouer les durs avec un talkie walkie, décider où vont les barrières pour éviter les morts et gérer les agents de sécurité.',
        ],
        'son-lumiere' => [
            'title' => 'Son et lumière',
            'description' => 'Quelques kilowatt de son, plein de lumières dans tous les sens et tout ça pour la soirée d\'intégration, le WEI et le M500.',
        ],
        'developpeur' => [
            'title' => 'Développeur (PHP, Laravel, Ionic)',
            'description' => 'Il y a toujours de nouvelles fonctionnalités à ajouter au site de l\'inté et à l\'application mobile. A quand un décompte en temps réel du nombre de tour de rond points !',
        ],
        'dj' => [
            'title' => 'DJ',
            'description' => 'Parce qu\'à la soirée d\'inté comme au WEI, on a besoin de vrai set !',
        ],
        'traduction' => [
            'title' => 'Traduction anglais',
            'description' => 'L\'UTT c\'est 24% d\'étrangers, et si on les intégrait aussi ?',
        ],
    ];

    public function hasAlreadyValidatedChallenge(int $id) {
        return count($this->challenges()->where('challenges.id', '=', $id)->wherePivot('validated', true)->get())>0?true:false;
    }

    public function challenges() {
        $pivots = ['submittedOn', 'validated', 'pic_url', 'last_update', 'update_author', 'message', 'team_id'];
        return $this->belongsToMany('App\Models\Challenge', 'challenge_validations')->withPivot($pivots)->where('user_id', '=', $this->id);
    }

    /**
     * Change the identifier for passport ('email' field by default, we want 'login')
     *
     * @param String $identifier the value of the 'username' parameter sent in the request
     * @return User
     */
    public static function findForPassport($identifier) {
        return User::where('login', $identifier)->first();
    }

    /**
     * Rewrite the way passport check if the password is right (because we encrypt the newcomer's passwords)
     *
     * @param String $password: the password sent as in the request
     * @return Boolean
     */
    public function validateForPassportPasswordGrant($password) {
        // decrypt the password of the user to compare it
        return password_verify($password, $this->password);
    }

    /**
     * Encrypt the given password and associate it to the user
     *
     * @param String $password: the password to set
     */
    public function setPassword($password) {
        $this->password = password_hash($password, PASSWORD_DEFAULT);
    }

    /*
     * If code postal is null then it's 0
     */
    public function getPostalCodeAttribute($value)
    {
        if(!$value)
            return 0;
        return $value;
    }

    /*
     * Accessors mail
     */
    public function getBestEmail()
    {
        if ($this->email) {
            return $this->email;
        }
        return $this->registration_email;
    }

    /**
     * Check if user is underaged for the wei start
     * @return boolean true if the user is underage for the wei
     */
    public function isUnderage()
    {
        if($this->birth) {
            return ($this->birth->add(new \DateInterval('P18Y')) >= (new \DateTime(Config::get('services.wei.start'))));
        }
        else if ($this->wei_majority !== null) {
            return !$this->wei_majority;
        }
        else if ($this->isStudent()) {
            return false;
        }
        return true;
    }

    /**
     * Scope a query to only include users that are newcomers.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeNewcomer($query)
    {
        return $query->where('is_newcomer', true);
    }

    /**
     * Scope a query to only include users that are students.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeStudent($query)
    {
        return $query->where('is_newcomer', false)->whereNotNull('etuutt_login');
    }

    /**
     * Query referrals newscomers
     */
    public function newcomers()
    {
        return $this->hasMany(User::class, 'referral_id', 'id');
    }

    /**
     * Define the One-to-Many relation with Message;
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function messages()
    {
        return $this->hasMany('App\Models\Message');
    }

    public function isStudent()
    {
        return !$this->is_newcomer && $this->etuutt_login;
    }

    public function isNewcomer()
    {
        return $this->is_newcomer;
    }

    /**
     * Return newcomers referal
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function godFather()
    {
        return $this->belongsTo(User::class, 'referral_id', 'id');
    }

    public function mailHistories()
    {
        return $this->hasMany(MailHistory::class);
    }
    public function getDates()
    {
        return ['created_at', 'updated_at', 'last_login', 'birth'];
    }

    /**
     * The chekins that belong to the User.
     */
    public function students()
    {
        return $this->belongsToMany(Checkin::class);
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
     * Test if the user can all of the dashboard
     * @return bool
     */
    public function isAdmin()
    {
        return ($this->admin == User::ADMIN_FULL);
    }

    /**
     * Test if the user can all of the dashboard
     * @return bool
     */
    public function isModerator()
    {
        return ($this->admin == User::ADMIN_MODERATOR);
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

        User::creating(function ($user) {
            // Generate login
            if (empty($user->login)) {
                $login = strtolower(mb_substr(mb_substr(preg_replace("/[^A-Za-z0-9]/", '', $user->first_name), 0, 1) . preg_replace("/[^A-Za-z0-9]/", '', $user->last_name), 0, 8));
                $i = '';
                while (User::where(['login' => $login . $i])->count()) {
                    if (empty($i)) {
                        $i = 1;
                    }
                    $i++;
                }
                $user->login = $login . $i;
            }

            // generate uuid
            $user->qrcode = Uuid::uuid4();
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

            if (!$this->isUnderage()) {
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

    public function isOrga() : bool {
        return $this->orga?true:false;
    }
}
