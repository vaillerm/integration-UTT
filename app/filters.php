<?php

/*
|--------------------------------------------------------------------------
| Application & Route Filters
|--------------------------------------------------------------------------
|
| Below you will find the "before" and "after" events for the application
| which may be used to do any work before or after a request into your
| application. Here you may also register your custom route filters.
|
*/

App::before(function($request)
{
	//
});


App::after(function($request, $response)
{
	//
});

/*
|--------------------------------------------------------------------------
| Authentication Filters
|--------------------------------------------------------------------------
|
| The following filters are used to verify that the user of the current
| session is logged into this application. The "basic" filter easily
| integrates HTTP Basic authentication for quick, simple checking.
|
*/

Route::filter('auth', function()
{
	if (Auth::guest())
	{
		if (Request::ajax())
		{
			return Response::make('Unauthorized', 401);
		}
		else
		{
			return Redirect::guest('login');
		}
	}
});


Route::filter('auth.basic', function()
{
	return Auth::basic();
});

/*
|--------------------------------------------------------------------------
| Guest Filter
|--------------------------------------------------------------------------
|
| The "guest" filter is the counterpart of the authentication filters as
| it simply checks that the current user is not logged in. A redirect
| response will be issued if they are, which you may freely change.
|
*/

Route::filter('guest', function()
{
	if (Auth::check()) return Redirect::to('/');
});

/*
|--------------------------------------------------------------------------
| CSRF Protection Filter
|--------------------------------------------------------------------------
|
| The CSRF filter is responsible for protecting your application against
| cross-site request forgery attacks. If this special token in a user
| session does not match the one given in this request, we'll bail.
|
*/

Route::filter('csrf', function()
{
	if (Session::token() != Input::get('_token'))
	{
		throw new Illuminate\Session\TokenMismatchException;
	}
});

/*
|--------------------------------------------------------------------------
| Oauth Filter
|--------------------------------------------------------------------------
|
| If the user was authenticated via OAuth, we set a session variable called
| "student_id" with... its student id inside (cf. OAuthController).
| It can't be faked so we can use it across all the website.
|
*/

Route::filter('oauth', function()
{
	if (Session::has('student_id') === false)
	{
		return Redirect::route('oauth.authorize');
	}

	$referral = Referral::find(Session::get('student_id'));
	if ($referral)
	{
		View::share('referral', $referral);
	}
});

/*
|--------------------------------------------------------------------------
| Dashboard Filter
|--------------------------------------------------------------------------
|
| Check that the user is referenced in the administrators table.
|
*/

Route::filter('admin', function()
{
	$user = DB::table('administrators')->where('student_id', Session::get('student_id'))->first();
	if ($user === null)
	{
		return Redirect::route('index');
	}
});
