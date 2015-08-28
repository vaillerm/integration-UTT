<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <title>Intégration UTT 2015</title>
        <link rel="stylesheet" href="{{ asset('css/bootstrap.min.css') }}">
        <link rel="stylesheet" href="{{ asset('css/homepage.css') }}">
        <meta name=viewport content="width=device-width, initial-scale=1">
    </head>
    <body>
        <div class="container">
            <div class="row">
                <div class="text-center">
                    <h1>Patience, le site de l'intégration ouvrira bientôt ses portes...</h1><br>

                    <a href="{{ route('menu') }}" class="btn btn-default">Non, moi je veux devenir parrain !</a>
                    <br><br>
                    <a href="{{ route('championship.display') }}" class="btn btn-success">Tableau des scores</a>
                </div>
            </div>

            <div class="row">
                <div class="text-center">
                    <a href="http://www.conforama.fr/magasins-conforama/TROYES?lat=0&long=0&name=TROYES"><img src="{{ asset('img/sponsors/conforama.png') }}" alt="" /></a>
                    <a href="http://www.yves-damonte.fr/"><img src="{{ asset('img/sponsors/damonte.png') }}" alt="" /></a>
                    <a href="http://www.memphis-coffee.com/maj/nos_retos-123.html"><img src="{{ asset('img/sponsors/memphis.png') }}" alt="" /></a>
                    <a href="http://mgel.fr"><img src="{{ asset('img/sponsors/mgel.png') }}" alt="" /></a>
                </div>
            </div>
        </div>
    </body>
</html>
