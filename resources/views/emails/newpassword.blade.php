@extends('layouts.email')

@section('title')
	Tes identifiants de connexion
@endsection

@section('content')
	<p>Bonjour <strong>{{ $user->fullName() }}</strong>,</p>
	<p>Afin de te permettre d'accèder au site de l'Intégration, nous t'avons créé des identifiants externes :</p>
	<ul>
		<li><b>Identifiant : </b> {{ $user->login }}</li>
		<li><b>Mot de passe : </b> {{ $password }}</li>
	</ul>
	<p>Pour les utiliser, rends toi sur <a style="color: #3c8dbc;" href="https://integration.utt.fr">integration.utt.fr</a> et clique sur "Je suis nouveau" (même si tu n'es pas nouveau).
		Tu pourras ensuite te connecter avec les identifiants précédents afin de te déclarer bénévole pour, par exemple, participer au WEI.
	</p>
	<p>À très bientôt pour l'Intégration UTT !</p>
@endsection
