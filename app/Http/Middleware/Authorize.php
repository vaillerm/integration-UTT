<?php

namespace App\Http\Middleware;

use Closure;
use EtuUTT;
use Authorization;
use Redirect;
use Auth;

class Authorize
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string  $group  Group of user : admin, ce, orga, referral, student
     * @param  string  $action  Specific action for this group of user
     * @return mixed
     */
    public function handle($request, Closure $next, $group, $action = '')
    {
        // Login/student verification
        if (in_array($group, ['student', 'admin', 'orga', 'ce', 'referral', 'volunteer'])
                && !EtuUTT::isAuth()) {
            return Redirect::route('index')->withError('Vous devez être connecté pour accéder à cette page');
        }

        // Login/newcomer verification
        if (in_array($group, ['newcomer'])
                && !Auth::check()) {
            return Redirect::route('newcomer.auth.login')->withError('Vous devez être connecté pour accéder à cette page');
        }

        // Volunteer verification
        if (in_array($group, ['admin', 'orga', 'ce', 'volunteer'])
                && !EtuUTT::student()->volunteer) {
            return Redirect::route('dashboard.students.profil')->withError('Veuillez remplir ce formulaire pour continuer :)');
        }

        // Other verification
        if (!Authorization::can($group, $action)) {
            return Redirect::back()->withError('Vous n\'avez pas le droit d\'accéder à cette page');
        }

        return $next($request);
    }
}
