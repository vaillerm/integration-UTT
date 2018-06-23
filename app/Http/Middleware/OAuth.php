<?php

namespace App\Http\Middleware;

use Closure;
use Session;
use Redirect;
use View;
use Auth;
use App\Models\User;

class OAuth
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
        if (!Auth::user() || Auth::user()->isNewcomer()) {
            return Redirect::route('oauth.auth');
        }
        View::share('student', Auth::user());

        return $next($request);
    }
}
