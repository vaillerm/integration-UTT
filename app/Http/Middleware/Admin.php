<?php

namespace App\Http\Middleware;

use Closure;
use DB;
use Session;
use Redirect;
use EtuUTT;

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
        if (!EtuUTT::isAuth() || !EtuUTT::student()->isAdmin())
        {
            return Redirect::route('index');
        }


        return $next($request);
    }
}
