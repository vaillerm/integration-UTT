@extends('layouts.newcomer')

@section('css')
		<link rel="stylesheet" href="{{ asset('css/flipclock.css') }}">
@endsection

@section('js')
    <script src="{{ asset('js/flipclock.min.js') }}"></script>
    <script>
    var countdown = $('.countdown').FlipClock(
        {{ (new DateTime(Config::get('services.wei.registrationStart')))->getTimestamp() - (new DateTime('now'))->getTimestamp() }},
        {
        countdown: true,
		clockFace: 'DailyCounter',
		language: 'french',
    });
    </script>
@endsection

@section('title')
Inscription au WEI
@endsection

@section('smalltitle')
Le Week-End d'Intégration
@endsection

@section('content')
    @if(Config::get('services.wei.open') === '-1')
        <h3 class="box-title">En raison de la situation sanitaire actuelle, nous ne pouvons pas organiser de WEI cette année.</h3>
    @elseif(Authorization::can('newcomer','wei'))

		<div class="box box-default">
			<div class="box-header with-border">
				<h3 class="box-title">Mon inscription au Week-End</h3>
			</div>
			<div class="box-body">
				<p>Le Week-End d’Intégration commence le vendredi {{ (new Datetime(Config::get('services.wei.start')))->format('j') }} septembre à 11h30 par un voyage en bus dans un lieu dont on garde le mystère (c’est pas en Creuse promis !). Durant ce week-end, de nombreuses activités, soirées et surprises te seront proposées, c’est surtout l’occasion de rencontrer pleins de nouveaux, des futurs potes ;-). On te ramène à Troyes le dimanche au soir vers 18h.</p>
				<p>Le prix du week-end est de {{ config('services.wei.price')/100 }}€, on te demandera également une caution de {{ config('services.wei.guaranteePrice')/100 }}.</p>


			@if(!Auth::user()->isPageChecked('profil'))
	            <div class="text-center">
	                <big>Tu dois compléter totalement ton profil pour pouvoir t'inscrire au week-end !</big><br/>
					<a href="{{route('newcomer.profil')}}" class="btn btn-primary">Compléter mon profil</a>
            	</div>

			@elseif(Config::get('services.wei.open') === '1')

				@if((new DateTime(Config::get('services.wei.registrationStart'))) > (new DateTime()))
					<div class="box box-default">
						<div class="box-header with-border">
							<h3 class="box-title">Ouverture des inscriptions pour le week-end dans...</h3>
						</div>
						<div class="box-body text-center">
							<div class="countdown hidden-xs" style="width:640px;margin:20px auto;"></div>
							<big class="visible-xs">{{ ((new DateTime(Config::get('services.wei.registrationStart')))->diff(new DateTime()))->format('%d jours %h heures %i minutes et %s secondes') }}</big>
						</div>
					</div>
				@elseif(!Auth::user()->wei && !Auth::user()->parent_authorization)
					<a href="{{route('newcomer.wei.pay')}}" class="btn btn-primary">S'inscrire au week-end</a><br/>
						<p>Si tu as le moindre souci, n'hésite pas à utiliser le bouton <em>Nous contacter</em> en haut à droite de la page !</p>
					<small>Note : ton inscription pour le week-end sera validée une fois que tu auras payé. Donc cette page s'affichera tant qu'il n'y aura aucun paiement enregistré. :)</small>
				@else

                    @if($wei)
                        <big>
                            <i class="fa fa-check-square-o" aria-hidden="true"></i>
                            Payer le week-end
                        </big><br/>
                    @else
                        <big><a href="{{ route('newcomer.wei.pay') }}">
                                <i class="fa fa-square-o" aria-hidden="true"></i>
                                Payer le week-end
                            </a></big><br/>
                    @endif

                    @if($sandwich)
                        <big>
                            <i class="fa fa-check-square-o" aria-hidden="true"></i>
                            Prendre le panier repas du vendredi midi
                        </big><br/>
                    @else
                        <big><a href="{{ route('newcomer.wei.pay') }}">
                                <i class="fa fa-square-o" aria-hidden="true"></i>
                                Prendre le panier repas du vendredi midi
                            </a></big><br/>
                    @endif

                    @if($guarantee)
                        <big>
                            <i class="fa fa-check-square-o" aria-hidden="true"></i>
                            Déposer la caution
                        </big><br/>
                    @else
                        <big><a href="{{ route('newcomer.wei.guarantee') }}">
                                <i class="fa fa-square-o" aria-hidden="true"></i>
                                Déposer la caution
                            </a></big><br/>
                    @endif

                    @if(Auth::user()->isUnderage())
                        @if(Auth::user()->parent_authorization)
                            <big>
                                <i class="fa fa-check-square-o" aria-hidden="true"></i>
                                Donner l'autorisation parentale
                            </big><br/>
                        @else
                            <big><a href="{{ route('newcomer.wei.authorization') }}">
                                    <i class="fa fa-square-o" aria-hidden="true"></i>
                                    Donner l'autorisation parentale
                                </a></big><br/>
                        @endif
                    @endif

                    <small>Note : pour les opérations manuelles (donner l'autorisation parentale, payer par chèque...), la case sera cochée une fois que l'action aura été faite grâce au stand qui sera installé pendant la semaine d'intégration à l'UTT.</small>
				@endif

			@else
                <a href="" class="btn btn-primary">L'inscription au WEI n'est pas encore ouverte !</a>
			@endif
			</div>
        </div>
		<div class="box box-default">
			<div class="box-header with-border">
				<h3 class="box-title">A ne pas oublier pour ton Week-End !</h3>
			</div>
			<div class="box-body">
				<ul>
					<li>Un sac de couchage chaud</li>
					<li>Ton déguisement</li>
					<li>Des vêtements qui ne craignent rien</li>
					<li>Des vêtements qui tiennent chaud</li>
					<li>Un k-way</li>
					<li>Ton autorisation parentale si tu es mineur</li>
					<li>Un ananas (primordial)</li>
					<li>Une boit à clous (primordial)</li>
				</ul>
			</div>
		</div>
    @elseif($count >= Config::get('services.wei.newcomerMax'))
        <div class="box box-default">
            <div class="box-header with-border">
                <h3 class="box-title">On est vraiment désolés, il n'y a plus de places disponibles pour le WEI :(</h3>
            </div>
        </div>
    @else
        <div class="box box-default">
            @if((new DateTime(Config::get('services.wei.registrationStart'))) > (new DateTime()))
                <div class="box box-default">
                    <div class="box-header with-border">
                        <h3 class="box-title">Ouverture des inscriptions pour le week-end dans...</h3>
                    </div>
                    <div class="box-body text-center">
                        {{ ((new DateTime(Config::get('services.wei.registrationStart')))->diff(new DateTime()))->format('%d jours %h heures %i minutes et %s secondes') }}
                        <div class="countdown hidden-xs" style="width:640px;margin:20px auto;"></div>
                        <big class="visible-xs">{{ ((new DateTime(Config::get('services.wei.registrationStart')))->diff(new DateTime()))->format('%d jours %h heures %i minutes et %s secondes') }}</big>
                    </div>
                </div>
            @else
            <div class="box-header with-border">
                <h3 class="box-title">Oups... Il semblerait que tu ne puisses pas t'inscire au WEI. Si tu penses qu'il s'agit d'une erreur, contacte nous.</h3>
                <p><a href="mailto:integration@utt.fr">integration@utt.fr</a></p>
            </div>
            @endif
        </div>
    @endif


@endsection
