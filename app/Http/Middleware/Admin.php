<?php

namespace App\Http\Middleware;

use Closure;
use DB;
use Session;
use Redirect;

class Admin
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {

        $user = DB::table('administrators')->where('student_id', Session::get('student_id'))->first();
        if ($user === null)
        {
            return Redirect::route('index');
        }


        return $next($request);
    }
}
