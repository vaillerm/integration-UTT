<?php

namespace App\Http\Middleware;

use Closure;
use DB;
use Session;
use Route;
use Request;
use Redirect;
use EtuUTT;

class Dashboard
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
        if (!EtuUTT::isAuth())
        {
            return Redirect::route('index');
        }
        else if (!EtuUTT::student()->volunteer && Request::route()->getName() != 'dashboard.students.profil'
            && Request::route()->getName() != 'dashboard.students.profil.submit'
            && Request::route()->getName() != 'dashboard.ce.firsttime') {
            return Redirect::route('dashboard.students.profil')->withError('Veuillez remplir ce formulaire pour continuer :)');
        }

        return $next($request);
    }
}
