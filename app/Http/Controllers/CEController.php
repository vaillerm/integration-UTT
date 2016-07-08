<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;

use EtuUTT;

class CEController extends Controller
{
    /**
     * Set student as CE and redirect to dashboard index
     *
     * @return Response
     */
    public function firstTime()
    {
        $student = EtuUTT::student();
        $student->ce = true;
        $student->save();

        return redirect(route('dashboard.index'));
    }
}
