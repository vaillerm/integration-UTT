@extends('layouts.email')

@section('title')
	Tes identifiants de connexion / Your login and password
@endsection

@section('content')
	<p>Bonjour <strong>{{ $user->fullName() }}</strong>,</p>
	<p>Afin de te permettre d'accéder au site de l'Intégration, nous t'avons créé des identifiants externes :</p>
	<ul>
		<li><b>Identifiant : </b> {{ $user->login }}</li>
		<li><b>Mot de passe : </b> {{ $password }}</li>
	</ul>
    @if($user->isNewcomer())
	<p>Pour les utiliser, rends toi sur <a style="color: #3c8dbc;" href="https://integration.utt.fr">integration.utt.fr</a> et clique sur "Je suis nouveau" (même si tu n'es pas nouveau).
		Tu pourras ensuite te connecter avec les identifiants précédents afin de te déclarer bénévole pour, par exemple, participer au WEI.
	</p>
	@else
	<p>Pour les utiliser, rends toi sur <a style="color: #3c8dbc;" href="https://integration.utt.fr">integration.utt.fr</a> et clique sur "Je suis nouveau".
		Tu pourras ensuite te connecter avec tes identifiants afin de voir toutes les informations sur l'intégration et pouvoir t'inscrire au WEI.
	</p>
	@endif
	<p>À très bientôt pour l'Intégration UTT !</p>

	<hr/>

	<p>Hello <strong>{{ $user->fullName() }}</strong>,</p>
	<p>
		In order to access the Integration UTT website, we created a login and a password for you :</p>
	<ul>
		<li><b>Login : </b> {{ $user->login }}</li>
		<li><b>Password : </b> {{ $password }}</li>
	</ul>
	<p>Use them by going to <a style="color: #3c8dbc;" href="https://integration.utt.fr">integration.utt.fr</a> and click on "Je suis nouveau".
		You will then be able get all the informations about the integration and register for the Weekend.
	</p>
	<p>See you soon at Intégration UTT !</p>
@endsection
