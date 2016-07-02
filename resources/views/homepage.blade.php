<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <title>Intégration UTT</title>
        <link rel="stylesheet" href="{{ @asset('css/bootstrap.min.css') }}">
        <link rel="stylesheet" href="{{ @asset('css/homepage.css') }}">
        <meta name=viewport content="width=device-width, initial-scale=1">

		<meta name="apple-mobile-web-app-title" content="Intégration UTT">
		<meta name="msapplication-TileColor" content="#3c8dbc">
		<meta name="application-name" content="Intégration UTT">

        <link rel="icon" href="{{ @asset('favicon.ico') }}" />
		<link rel="apple-touch-icon" sizes="120x120" href="{{ @asset('img/icons/apple-touch-icon-120x120.png') }}">
		<link rel="apple-touch-icon" sizes="114x114" href="{{ @asset('img/icons/apple-touch-icon-114x114.png') }}">
		<link rel="apple-touch-icon" sizes="76x76" href="{{ @asset('img/icons/apple-touch-icon-76x76.png') }}">
		<link rel="apple-touch-icon" sizes="72x72" href="{{ @asset('img/icons/apple-touch-icon-72x72.png') }}">
		<link rel="apple-touch-icon" sizes="60x60" href="{{ @asset('img/icons/apple-touch-icon-60x60.png') }}">
		<link rel="apple-touch-icon" sizes="57x57" href="{{ @asset('img/icons/apple-touch-icon-57x57.png') }}">
		<link rel="icon" type="image/png" href="{{ @asset('img/icons/favicon-16x16.png') }}" sizes="16x16">
		<link rel="icon" type="image/png" href="{{ @asset('img/icons/favicon-32x32.png') }}" sizes="32x32">
		<link rel="icon" type="image/png" href="{{ @asset('img/icons/favicon-96x96.png') }}" sizes="96x96">
    </head>
    <body>
        <div class="container">
            <div class="row">
                <div class="text-center">
                    <h1>Bienvenue sur le site de l'intégration</h1><br>
                    <!-- <a href="{{ route('menu') }}" class="btn btn-primary">Je suis nouveau !</a> -->
                    <!-- <a href="{{ route('championship.display') }}" class="btn btn-success">Tableau des scores</a> -->

                    <br/><br/><br/>
                    <p>Tu souhaites devenir parrain, marraine, chef d'équipe ou tout simplement bénévole pour cette folle aventure ?</p>
                    <a href="{{ route('menu') }}" class="btn btn-default">Je suis étudiant à l'UTT</a>
<!--                    <br><br>-->
                </div>
            </div>

            <div class="row sponsor">
                <div class="text-center">
                    <a href="http://www.yves-damonte.fr/"><img src="{{ @asset('img/sponsors/damonte.png') }}" alt="" /></a>
                    <!-- <a href="http://www.memphis-coffee.com/maj/nos_retos-123.html"><img src="{{ @asset('img/sponsors/memphis.png') }}" alt="" /></a> -->
                    <!-- <a href="http://mgel.fr"><img src="{{ @asset('img/sponsors/mgel.png') }}" alt="" /></a> -->
                </div>
            </div>
        </div>

    </body>
</html>
