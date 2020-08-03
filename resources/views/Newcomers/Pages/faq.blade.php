@extends('layouts.newcomer')

@section('title')
FAQ
@endsection

@section('smalltitle')
Frequently Asked Questions
@endsection

@section('content')
    <div class="box box-default">
        <div class="box-header with-border">
            <h3 class="box-title">La semaine d'intégration</h3>
        </div>
        <div class="box-body">
	            <h4><a href="#question100" data-toggle="collapse">Quand est-ce que je dois venir à l’UTT ?</a></h4>
	            <p id="question100" class="collapse">
	                Pour les post-bac : {{ Config::get('services.reentry.tc.date') }} à {{ Config::get('services.reentry.tc.time') }}.<br/>
	                Pour les branches (bac +2) : {{ Config::get('services.reentry.branches.date') }} à {{ Config::get('services.reentry.branches.time') }}.
	                Pour les masters : {{ Config::get('services.reentry.masters.date') }} à {{ Config::get('services.reentry.masters.time') }}.
	                <!-- <strong>On t’offre le petit-déjeuner !</strong> # TODO : Uncomment year after COVID -->
	            </p>

				<h4><a href="#question200" data-toggle="collapse">Quel est l’emploi du temps de la semaine d’intégration ?</a></h4>
	            <p id="question200" class="collapse">
					Il t’a été envoyé par email. Tu en auras une recevera une copie à ton arrivée à l’UTT.
	            </p>

			    <h4><a href="#question300" data-toggle="collapse">Quand est-ce que je dois amener mon déguisement ?</a></h4>
	            <p id="question300" class="collapse">
	                Il te sera utile dès le premier après-midi de la semaine, on te conseille de faire un déguisement qui déchire, pour rendre tes chefs d’équipes fiers !
	            </p>

			    <h4><a href="#question400" data-toggle="collapse">Les repas sont-ils compris ?</a></h4>
	            <p id="question400" class="collapse">
                    Malheureusement, cette année en raison de la COVID-19 nous n'avons pas le droit de vous servir de repas.
					<!-- Le jour de ton arrivée (le lundi ou mardi), le petit déjeuner est offert par le BDE.
					Pour les repas du lundi soir (pour les TC), mardi soir (pour tout le monde), jeudi midi et vendredi midi, il faudra prévoir un peu de monnaie mais pas de panique, cela reste à des prix très étudiants (entre 2 et 4€). TODO : Remove year after COVID -->
				</p>

			    <h4><a href="#question500" data-toggle="collapse">Est-ce qu'il y a des cours la première semaine ?</a></h4>
	            <p id="question500" class="collapse">
					Non. Juste des réunions avec les responsables de la formation. Tu passeras un tiers de ton temps avec eux, un tiers de ton temps avec nous, l’intégration, et le reste en temps libre.
	            </p>

			    <h4><a href="#question600" data-toggle="collapse">Qu’est-ce que c’est les défis TCs ?</a></h4>
	            <p id="question600" class="collapse">
					Cela concerne uniquement les étudiants qui entrent en Tronc Commun. C’est un concours qui se fait par équipes afin de trouver une solution pour répondre à des défis scientifiques. Par exemple, les défis ont pu être "Réaliser une tour de spaghettis la plus haute possible" ou "Catapulte avec du carton"...
				</p>

			    <h4><a href="#question700" data-toggle="collapse">Quand ont lieu les inscriptions ?</a></h4>
	            <p id="question700" class="collapse">
	                Elles auront lieu tout au long de la semaine sur des créneaux définis. Nous te conseillons donc d’amener le nécessaire à ton inscription dès le premier jour.
	            </p>
			</div>
		</div>

		    <div class="box box-default">
	        <div class="box-header with-border">
	            <h3 class="box-title">Le WEI</h3>
	        </div>
	        <div class="box-body">

				<h4><a href="#question2000" data-toggle="collapse">Qu’est ce que c’est le WEI ?</a></h4>
				<p id="question2000" class="collapse">
					WEI signifie Week-End d’Intégration, c’est un événement qui clôture en beauté la semaine d’intégration. Pleins de surprises t’y attendent c’est également un bon moment pour se faire pleins de potes !
				</p>

				<h4><a href="#question2100" data-toggle="collapse">C’est quand ?</a></h4>
				<p id="question2100" class="collapse">
					On part le vendredi à 11h30 et on revient le dimanche vers 18h. On part de l’UTT mais au retour tu auras le choix entre dépose à l’UTT ou au centre-ville.
				</p>

				<h4><a href="#question2200" data-toggle="collapse">C’est où ?</a></h4>
				<p id="question2200" class="collapse">
					Ca c’est une surprise ! ;) On peut juste te dire qu’on ne va pas <strong>dans la Creuse</strong>.
				</p>

				<h4><a href="#question2300" data-toggle="collapse">Qu’est-ce qu’on y fait ?</a></h4>
				<p id="question2300" class="collapse">
					On s’amuse, on rencontre les autres nouveaux et on désigne les vainqueurs de l'<strong>histoire</strong> !
				</p>

				<h4><a href="#question2400" data-toggle="collapse">Qu’est-ce que je dois emmener ?</a></h4>
				<p id="question2400" class="collapse">
					Un sac de couchage (chaud), des vêtements qui ne craignent rien et d’autres qui tiennent chaud, un k-way, ton déguisement, un ananas, une boite à clous et ton autorisation parentale si tu es mineur.
				</p>

				<h4><a href="#question2500" data-toggle="collapse">Est-ce que je peux venir si je suis mineur(e) ?</a></h4>
				<p id="question2500" class="collapse">
					Oui, évidemment ! Mais il faudra nous fournir l’autorisation parentale que tu as reçue ou téléchargeable <a href="{{asset('docs/autorisation.pdf')}}">ici</a> remplie et signée par tes parents.
				</p>

				<h4><a href="#question2600" data-toggle="collapse">Combien ça coûte ?</a></h4>
				<p id="question2600" class="collapse">
					La totalité du week-end coûte {{ Config::get('services.wei.price') }}€, nourriture et transport compris, prévoit juste de la monnaie pour tes consommations en soirée (entre 1€ et 2€ le verre).
				</p>

				<h4><a href="#question2700" data-toggle="collapse">Les repas sont-ils compris ?</a></h4>
				<p id="question2700" class="collapse">
					Pendant le week-end, oui, et ça va être bon ! ;-)
				</p>

				<h4><a href="#question2800" data-toggle="collapse">Faut-il prévoir de l’argent ?</a></h4>
				<p id="question2800" class="collapse">
					Oui, pour payer tes éventuelles consommations au bar. Il ne sera pas possible d’accéder à un distributeur sur place, des boissons non alcoolisées seront également proposées.
				</p>

				<h4><a href="#question2900" data-toggle="collapse">Est-ce que l’inté à l’UTT, c’est un bizutage ?</a></h4>
				<p id="question2900" class="collapse">
					Non, bien sûr. On ne te forcera jamais à rien faire ! Tu es libre de venir et toutes nos activités sont anti-bizutages, nous y tenons ! Tous nos bénévoles signent une charte de respect de ces engagements.<br>
					Un numéro d'urgence vous sera communiqué à la rentrée si jamais une et une seule dérive survenait, des mesures contre le responsable de l'incident seraient immédiatement prises.
				</p>
        </div>
    </div>
@endsection
