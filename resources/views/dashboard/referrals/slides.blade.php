<!DOCTYPE html>
<html>
	<head>
		<link href='https://fonts.googleapis.com/css?family=Open+Sans:400,300,700,600,800' rel='stylesheet' type='text/css'>
		<meta charset="utf-8" />
		<style>
			@page {
				margin: 0 0;
				size: 1920px 1080px;
				/*size: 50.8cm 28.575cm;*/
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
				width: 1920px;
				height: 1080px;

				position: relative;
				overflow: hidden;
				margin:auto;
				margin-bottom: 20px;

				background-repeat: no-repeat;
				background-image: url("{{ asset('/img/diapo-bg.png') }}");
				background-size: 100% 100%;
				background-position: center;

				text-align:left;
			}

			.referral {
				color: gray;
				height: 177px;
				padding-top: 70px;
				font-size: 70px;
				text-align: left;
				padding-left: 230px;
				padding-right: 320px;
				color: white;
				font-weight: bold;
				text-shadow: 0px 0px 9px #777;
			}

			.referral img {
				position: absolute;
				left: 20px;
				top: 20px;
				width: 168px;
			}

			.godsons {
				height:833px;
			}

			.godsons ul {
				position: relative;
				top: 50%;
				transform: translateY(-50%);
				margin:0;
			}

			.godsons li {
				line-height: 2.5cm;
				font-size: 2.5cm;
				vertical-align: middle;
				margin-bottom: 30px;
				margin-left: 150px;
				color: white;
				list-style-type: none;
			}

			.godsons li:before {
				content: "○";
				margin-right: 30px;
				vertical-align: top;
				font-weight: bold;
				font-size: 0.8em;
				color: #348FB5;
			}

		</style>
	</head>
	<body>
		<p class="page-indicator">Pour imprimer, utilisez Google Chrome. Pour faire un PDF de plusieurs pages, choisissez "Enregistrer au format PDF" avec aucune marge (ne passez pas par PDF Creator ou équivalent, le PDF sera très lourd si vous en imprimez beaucoup). Faites des groupes de 50 à 100 pages en fonction de la puissance de votre PC.</p>
		@foreach($referrals as $referral)
			<div class="page">
					<div class="referral">
						<img src="{{ asset('/uploads/students-trombi/'.$referral->student_id.'.jpg') }}" alt="pic"/>
						{{$referral->first_name}} {{ strtoupper($referral->last_name) }}
					</div>
					<div class="godsons">
						<ul>
							@foreach($referral->newcomers as $newcomer)
								<li>{{ $newcomer->first_name }} {{ strtoupper($newcomer->last_name) }}</li>
							@endforeach
						</ul>
					</div>
			</div>
		@endforeach
	</body>
</html>
