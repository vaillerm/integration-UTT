<?php

namespace App\Classes;

use App\Models\Student;
use Session;

class EtuUTT {

    /**
     * The currently authenticated student.
     *
     * @var \App\Models\Student
     */
	protected $student;


	/**
	 * Determine if the current user is authenticated.
	 *
	 * @return bool
	 */
    public function isAuth()
    {
        return ! is_null($this->student());
    }

    /**
     * Get the currently authenticated student.
     *
     * @return \App\Models\Student|null
     */
    public function student()
    {
        // If we've already retrieved the user for the current request we can just
        // return it back immediately. We do not want to fetch the user data on
        // every call to this method because that would be tremendously slow.
        if (! is_null($this->student)) {
            return $this->student;
        }

		$id = Session::get('student_id');

        // First we will try to load the user using the identifier in the session if
        // one exists. Otherwise we will check for a "remember me" cookie in this
        // request, and if one exists, attempt to retrieve the user using that.
        $student = null;
        if (! is_null($id)) {
	        $student = Student::find(Session::get('student_id'));
        }

		if($student === null && $id !== null) {
			Session::forget('student_id');
			abort(500);
		}

		return $student;
    }
}
