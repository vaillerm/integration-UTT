<!DOCTYPE html>
<html>
	<head>
		<link href='https://fonts.googleapis.com/css?family=Open+Sans:400,300,700,600,800' rel='stylesheet' type='text/css'>
		<meta charset="utf-8" />
		<style>
			@page {
				size: A4 landscape;
			}

			html, body {
				margin: 0;
				padding: 0;
				width: 100%;
				height: 100%;
				font-family: 'Open Sans', sans-serif;
				font-size: 3.81mm;
			}

			.page-indicator {
				width:100%;
				text-align:center;
				display: block;
			}
			@media print
			{
				.page-indicator
				{
					display: none !important;
				}
			}

			.page {
				page-break-after: always;
				width: 29.7cm;
				height: 21cm;

				background-image: url("{{ @asset('/img/letter/bg.jpg') }}");
				background-repeat: no-repeat;
				background-size: 100% 100%;
				position: relative;
				overflow: hidden;
				margin:auto;
				margin-bottom: 20px;
				color: white;
			}

			div {
				position: absolute;
			}

			.top {
				z-index: 2;
				top: 0;
				left: 0;
				position: absolute;
				width: 100%;
			}

			.parrain {
				left: 5.07mm;
				top: 5.07mm;
				z-index: 3;
			}

			.parrain .photo {
			    width: 20.40mm;
			    height: 25.65mm;
			    position: absolute;
			    background-position: center center;
			    background-size: cover;
			}

			.parrain .nom_bulle {
				top: 0;
				left: 23.70mm;
				width: 203.19mm;
				display: flex;
			}

			.parrain .nom {
				left: 0mm;
				top: 0.84mm;
				font-weight: 600;
				font-size: 1.7em;
				position: relative;
				white-space: nowrap;
			}

			.famille {
				text-transform: uppercase;
			}

			.parrain .nom div {
				min-width: 44mm;
				display: inline-block;
				position: relative;
			}

			.parrain .pointe_bulle {
				position: relative;
				padding-top: 4.23mm;
				padding-left: 4.23mm;
				opacity: 0.8;
			}

			.parrain .pointe_bulle img {
				width: 1cm
			}

			.parrain .bulle {
				background-color: rgba(255, 255, 255, 0.8);
				position: relative;
				border-radius: 5mm;
				padding: 4mm 4mm;
				color: black;
				font-size: 0.85em;
				min-height: 11.85mm;
				text-align: justify;
			}

			.parrain .tel {
				left: 25.39mm;
				top: 11.85mm;
				white-space: nowrap;
			}

			.parrain .tel img {
				width: 0.8em;
				vertical-align: middle;
				padding-right: 1.18mm;
				padding-left: 0.26mm;
			}

			.parrain .tel span {
				vertical-align: middle;
			}

			.parrain .mail {
				left: 25.39mm;
				top: 17.78mm;
				white-space: nowrap;
			}

			.parrain .mail .img {
				padding-right: 1.19mm;
				display: inline-block;
			}

			.parrain .mail span {
				vertical-align: middle;
			}

			.parrain .info {
				top: 26mm;
				white-space: nowrap;
				font-size: 0.7em;
				font-weight: 300;
			}

			.parrain .info p {
				margin: 1mm 0 1.5mm 0;
			}

			.blocs {
				left: 10.16mm;
				top: 82.97mm;
				overflow: hidden;
			}

			.blocs div {
				position: relative;
			}

			.blocs .acces {
				width: 91.43mm;
			}

			.blocs .acces .info {
				background-color: rgba(143, 39, 203, 0.8);
				font-size: 0.85em;
				padding: 2.54mm;
			}

			.blocs .acces .info .titre {
				text-transform: uppercase;
				font-weight: 700;
				width: 100%;
				text-align: center;
				margin-bottom: 1.69mm;
				font-size: 1.07em;
			}

			.blocs .acces .info .texte {
				text-align: justify;
				font-size: 0.9em;
			}

			.blocs .acces .id {
				background-color: black;
				font-size: 0.8em;
				padding: 1.69mm 2.53mm;
			}

			.blocs .equipe {
				width: 220mm;
			}

			.blocs .equipe .titre {
				width: 100%;
				background-image: linear-gradient(to right, white 10%, gray 60%, black 90%);
				color: black;
				padding: 1.27mm 3.39mm;
				box-sizing: border-box;
			}

			.blocs .equipe .titre .nom {
				font-size: 1.4em;
				font-weight: bold;
			}

			.blocs .equipe .image {
				width: 42.33mm;
				height: 42.33mm;
				min-width: 42.33mm;
			    background-position: center center;
			    background-size: cover;
			}

			.blocs .equipe .desc {
				display: flex;
			}

			.blocs .equipe .texte {
				background-color: white;
				width: 100%;
				color: black;
				padding: 4.23mm 32.17mm 4.23mm 4.23mm;
				font-size: 0.75em;
				text-align: justify;
			}


			.oscar {
				z-index: 1;
				top: 0;
				left: 0;
				position: absolute;
				width:100%;
			}

			.facebook {
				z-index: 1;
				bottom: 5mm;
				left: 5mm;
				position: absolute;
				line-height: 6mm;
				height: 6mm;
			}
			.facebook img {
				height: 100%;
				vertical-align: middle;
			}
			.facebook span {
				vertical-align: middle;
				display:inline-block;
				height: 100%;
				margin-left:1mm;
				font-size: 100%;
			}

		</style>
	</head>
	<body>
		<p class="page-indicator">L'impression fonctionne mieux sous Google Chrome. Faites des groupes de 50 à 100 pages, en fonction de la puissance de votre PC.</p>
		@foreach($newcomers as $newcomer)
			<style>
				#parrain_photo_{{ $newcomer->id }} {
					background-image: url("{{ @asset('/uploads/students-trombi/'.$newcomer->referral_id.'.jpg') }}");
				}

				#equipe_image_{{ $newcomer->id }} {
					background-image: url("{{ @asset('/uploads/teams-logo/'.$newcomer->team->id.'.'.$newcomer->team->img) }}");
				}
			</style>
			<span class="page-indicator">{{ ++$i }}/{{ $count }}</span>
			<div class="page">
				@if($newcomer->referral)
					<img class="top" src="{{ @asset('/img/letter/top.png') }}" />
					<div class="parrain">
						<div class="photo" id="parrain_photo_{{ $newcomer->id }}"></div>
						<div class="nom_bulle">
							<div class="nom"><div>{{ $newcomer->referral->first_name }} <span class="famille">{{ $newcomer->referral->last_name }}</span></div></div>
							<div class="pointe_bulle">
								<img src="{{ @asset('/img/letter/bubble_point.png') }}" />
							</div>
							<div class="bulle">{!! nl2br(e($newcomer->referral->referral_text)) !!}</div>
						</div>
						<div class="tel">
							<img src="{{ @asset('/img/letter/phone.svg') }}" />
							<span>{{ $newcomer->referral->phone }}</span>
						</div>
						<div class="mail">
							<span class="img">@</span>
							<span>{{ $newcomer->referral->email }}</span>
						</div>
						<div class="info">
								<p>
								@if($newcomer->referral->sex == 0)
									Lui, c'est <strong>ton parrain</strong> !
									Son rôle est de te guider durant<br/>
									toute l'intégration, et au-delà ! On te conseille de<br/>
									prendre contact avec lui rapidement !
								@else
									Elle, c'est <strong>ta marraine</strong> !
									Son rôle est de te guider durant<br/>
									toute l'intégration, et au-delà ! On te conseille de<br/>
									prendre contact avec elle rapidement !
								@endif
							</p>
							<p>
								<strong>Tu n'oses pas faire le premier pas ?</strong><br/>
								Rendez-vous sur <u>integration.utt.fr</u> !
							</p>
						</div>
					</div>
				@endif
				<img class="oscar" src="{{ @asset('/img/letter/oscar.png') }}" />
				<div class="blocs">
					<div class="acces">
						<div class="info">
							<div class="titre">
								Ton accès au site de l'intégration
							</div>
							<div class="texte">
								<strong>{{ $newcomer->first_name }} <span class="famille">{{ $newcomer->last_name }}</span></strong>,
								voici ton compte pour accéder au site de l'intégration sur <u>integration.utt.fr</u><br/>
								Tu pourra y trouver des informations sur ta première semaine, l'inscription au week-end, des bons plans et plus encore !<br/>

							</div>
						</div>
						<div class="id">
	 						• Identifiant : <b>{{ $newcomer->login }}</b><br/>
	 						• Mot de passe : <b>{{ Crypt::decrypt($newcomer->password) }}</b>
						</div>
					</div>
					<div class="equipe">
						<div class="titre">
							<span class="nom">{{ $newcomer->team->name }}</span>, ton équipe !
						</div>
						<div class="desc">
							<div class="image" id="equipe_image_{{ $newcomer->id }}"></div>
							<div class="texte">
								{!! nl2br(e($newcomer->team->description)) !!}
							</div>
						</div>
					</div>
				</div>
				<div class="facebook">
					<img src="{{ @asset('/img/letter/facebook.png') }}" alt="Facebook"> <span>Integration UTT</span>
				</div>
			</div>
		@endforeach
	</body>
</html>
