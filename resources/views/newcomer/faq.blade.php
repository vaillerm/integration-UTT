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
	                Pour les PostBac : lundi 5 septembre à 8h.<br/>
	                Pour les branches (Bac+2) et les masters : mardi 5 septembre à 8h.
	                <strong>On t’offre le petit-déjeuner !</strong>
	            </p>

				<h4><a href="#question200" data-toggle="collapse">Quel est l’emploi du temps de la semaine d’intégration ?</a></h4>
	            <p id="question200" class="collapse">
					Il t’a été envoyé par lettre avec tes identifiants pour le site d’intégration, le nom de ton parrain et le nom de ton équipe. Tu en auras une autre copie à ton arrivée à l’UTT.
	            </p>

			    <h4><a href="#question300" data-toggle="collapse">Quand est-ce que je dois amener mon déguisement ?</a></h4>
	            <p id="question300" class="collapse">
	                Il te sera utile à partir du mardi midi, on te conseille de faire un déguisement qui déchire, pour rendre tes chefs d’équipes fières !
	            </p>

			    <h4><a href="#question400" data-toggle="collapse">Les repas sont-ils compris ?</a></h4>
	            <p id="question400" class="collapse">
					Le jour de ton arrivée, le petit déjeuner est offert par le BDE.
					Pour les repas du lundi soir (pour les TC), mardi soir, et vendredi midi, il faudra prévoir de la monnaie pas de panique, cela reste à des prix très etudiants ! ( entre 2 et 4€)
				</p>

			    <h4><a href="#question500" data-toggle="collapse">Est-ce qu'il y a des cours la première semaine ?</a></h4>
	            <p id="question500" class="collapse">
					Non. Juste des réunions avec les responsables de la formation. Tu passeras un tiers de ton temps avec eux, un tiers de ton temps avec nous, l’intégration, et le reste en temps libre.
	            </p>

			    <h4><a href="#question600" data-toggle="collapse">Qu’est ce que c’est les défis TCs ?</a></h4>
	            <p id="question600" class="collapse">
					Cela concerne uniquement les étudiants qui entrent en Tronc Commun. C’est un concours qui se fait par équipes afin de trouver une solution pour répondre à des défis scientifiques. L’année dernière, par exemple, un des défis était : "Réaliser une tour de spaghettis la plus haute possible"
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
					WEI signifie Week End d’Intégration, c’est un événement qui clôture en beauté la semaine d’intégration. Pleins de surprises t’y attendent c’est également un bon moment pour se faire pleins de potes !
				</p>

				<h4><a href="#question2100" data-toggle="collapse">C’est quand ?</a></h4>
				<p id="question2100" class="collapse">
					On part le vendredi à 11h30 et on revient le dimanche vers 19h. On part de l’UTT mais au retour tu auras le choix entre dépose à l’UTT ou à la gare.
				</p>

				<h4><a href="#question2200" data-toggle="collapse">C’est où ?</a></h4>
				<p id="question2200" class="collapse">
					Ca c’est une surprise ;) On peut juste de dire qu’on va pas à Chamonix !
				</p>

				<h4><a href="#question2300" data-toggle="collapse">Qu’est-ce qu’on y fait ?</a></h4>
				<p id="question2300" class="collapse">
					On s’amuse, on rencontre les autres nouveaux et on désigne les vainqueurs de l’oscar du meilleur film !
				</p>

				<h4><a href="#question2400" data-toggle="collapse">Qu’est-ce que je dois emmener ?</a></h4>
				<p id="question2400" class="collapse">
					Un duvet, des vêtements qui ne craignent rien et d’autres qui tiennent chaud, un k-way, ton déguisement.
				</p>

				<h4><a href="#question2500" data-toggle="collapse">Est-ce que je peux venir si je suis mineur(e) ?</a></h4>
				<p id="question2500" class="collapse">
					Oui, évidemment ! Mais il faudra nous fournir l’autorisation parentale située dans le N’UTT que tu as reçu, ou téléchargeable <a href="{{asset('docs/autorisation.png')}}">ici</a> remplie et signée par tes parents.
				</p>

				<h4><a href="#question2600" data-toggle="collapse">Combien ça coûte ?</a></h4>
				<p id="question2600" class="collapse">
					La totalité du week end coûte 55€, nourritures et transports compris, prévoit juste de la monnaie pour tes consommations en soirée, (entre 1€ et 2€ le verre)
				</p>

				<h4><a href="#question2700" data-toggle="collapse">Les repas sont-ils compris ?</a></h4>
				<p id="question2700" class="collapse">
					Pendant le Week-End, oui et ça va être bon ;-)
				</p>

				<h4><a href="#question2800" data-toggle="collapse">Faut-il prévoir de l’argent ?</a></h4>
				<p id="question2800" class="collapse">
					Oui, pour payer tes éventuelles consommations au bar. Il ne sera pas possible d’accéder à un distributeur sur place, des boissons non alcoolisé te seront proposées.
				</p>

				<h4><a href="#question2900" data-toggle="collapse">Est-ce que l’inté à l’UTT, c’est un bizutage ?</a></h4>
				<p id="question2900" class="collapse">
					Non, bien sûr. On ne te forcera jamais à rien faire ! Tu es libre de venir et toutes nos activités sont anti-bizutages, nous y tenons ! Tous nos bénévoles signent une charte de respect de ces engagements.
				</p>
        </div>
    </div>
@endsection
