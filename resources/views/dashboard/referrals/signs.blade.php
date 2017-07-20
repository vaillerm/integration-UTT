<!DOCTYPE html>
<html>
	<head>
		<link href='https://fonts.googleapis.com/css?family=Open+Sans:400,300,700,600,800' rel='stylesheet' type='text/css'>
		<meta charset="utf-8" />
		<style>
			@page {
				margin: 0 0;
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

				position: relative;
				overflow: hidden;
				margin:auto;
				margin-bottom: 20px;

				text-align:center;
			}

			.referral {
				color: gray;
				width:100%;
				height:10%;
				line-height: 2cm;
				font-size: 1.5cm;
			}

			.godsons {
				width:100%;
				height:90%;
			}

			.subgodsons {
				position: relative;
				top: 50%;
				transform: translateY(-50%);
			}

			.godson {
				line-height: 2.5cm;
				font-size: 2.5cm;
				vertical-align: middle;
				margin-bottom:0.7cm
			}

			#filigrane{
				position:absolute;
				top:0;
				left:0;
				width:100%;
				height: 100%;
				background-repeat: no-repeat;
				background-image: url("{{ asset('/img/logo.png') }}");
				background-size: 90% 90%;
				background-position: center;
				filter:alpha(opacity=10);
				opacity: 0.1;
				-moz-opacity:0.1;
			}
		</style>
	</head>
	<body>
		<p class="page-indicator">Pour imprimer, utilisez Google Chrome. Pour faire un PDF de plusieurs pages, choisissez "Enregistrer au format PDF" avec aucune marge (ne passez pas par PDF Creator ou équivalent, le PDF sera très lourd si vous en imprimez beaucoup). Faites des groupes de 50 à 100 pages en fonction de la puissance de votre PC.</p>
		@foreach($referrals as $referral)
			<div class="page">
					<div id="filigrane"></div>
					<div class="referral">{{$referral->first_name}} {{ strtoupper($referral->last_name) }}</div>
					<div class="godsons">
						<div class="subgodsons">
						@foreach($referral->newcomers as $newcomer)
							<div class="godson">{{ $newcomer->first_name }} {{ strtoupper($newcomer->last_name) }}</div>
						@endforeach
						</div>
					</div>
			</div>
		@endforeach
	</body>
</html>
