<?php

namespace App\Http\Middleware;

use Closure;
use Session;
use Redirect;
use View;
use App\Models\Referral;

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

        if (Session::has('student_id') === false)
        {
                return Redirect::route('oauth.auth');
        }

        $referral = Referral::find(Session::get('student_id'));
        if ($referral)
        {
                View::share('referral', $referral);
        }

        return $next($request);
    }
}
