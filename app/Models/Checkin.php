<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use App\Models\Student;
use Auth;

class Checkin extends Model
{

    /**
     * @var string
     */
    public $table = 'checkins';

    public $fillable = ['name', 'prefilled'];

    public $hidden = [
        'created_at',
        'updated_at',
    ];

    /**
	 * Define constraints of the Model's attributes for store action
     * from api requests
	 *
	 * @return array
	 */
	public static function apiStoreRules() {
		return [
			'name' => 'required|string|unique:checkins,name'
		];
	}

    /**
	 * Define constraints of the Model's attributes for store action
     * from web requests
	 *
	 * @return array
	 */
	public static function webStoreRules() {
		return [
			'name' => 'required|string|unique:checkins,name',
            'students' => 'required|array',
            'students.*' => 'exists:students,id'
		];
	}

    /**
	 * Define constraints of the Model's attributes for store action
	 *
	 * @return array
	 */
	public static function addStudentRules() {
		return [
			'email' => 'required|email|exists:students,email'
		];
	}

    /**
     * The students that belong to the Checkin.
     */
    public function students()
    {
        return $this->belongsToMany(Student::class);
    }

}
