<?php

namespace App\Http\Controllers;

use App\Http\Requests;
use App\Models\Student;

use View;

/**
 * Handle student management pages and administrators actions
 */
class StudentsController extends Controller {

    /**
     * Display student list
     *
     * @param  string $filter
     * @return Response
     */
    public function list($filter = '')
    {
        $students = Student::orderBy('last_name', 'asc');
        switch ($filter) {
            case 'admin':
                $students = $students->where('admin', '=', Student::ADMIN_FULL);
                break;
            case 'orga':
                $students = $students->where('orga', '=', true);
                break;
            case 'referral':
                $students = $students->where('referral', '=', true);
                break;
            case 'ce':
                $students = $students->where('ce', '=', true);
                break;
            case 'volunteer':
                $students = $students->where('volunteer', '=', true);
                break;
        }
        $students = $students->get();
        return View::make('dashboard.students.list', [
            'students' => $students,
            'filter' => $filter
        ]);
    }
}
