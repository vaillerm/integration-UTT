@extends('layouts.master')

@section('title')
Accueil
@endsection

@section('css')
<link rel="stylesheet" href="{{ @asset('css/bootstrap.min.css') }}" media="screen">
<link rel="stylesheet" href="{{ @asset('css/homepage.css') }}">
@endsection

@section('bodycontent')
    <div class="container">
        <div class="row">
            <div class="text-center">
                <h1>Bienvenue sur le site de l'intégration</h1><br>
                <a href="{{ route('newcomer.auth.login') }}" class="btn btn-primary">Je suis nouveau !</a>
                <!-- <a href="{{ route('championship.display') }}" class="btn btn-success">Tableau des scores</a> -->

                <br/><br/><br/>
                <p>Tu souhaites devenir parrain, marraine, chef d'équipe ou tout simplement bénévole pour cette folle aventure ?</p>
                <a href="{{ route('menu') }}" class="btn btn-default">Je suis étudiant à l'UTT</a>
    <!--                    <br><br>-->
            </div>
        </div>

        <div class="row sponsor">
            <div class="text-center">
                <?php
                    $sponsors = [];
                    $sponsors[] = [ 'link' => 'http://www.accesbureautique.fr/', 'img' => @asset("img/sponsors/access.png"), 'alt' => 'Access Bureautique' ];
                    $sponsors[] = [ 'link' => 'http://www.ada.fr/location-voiture-troyes.html', 'img' => @asset("img/sponsors/ada.png"), 'alt' => 'ADA Location de véhicules' ];
                    $sponsors[] = [ 'link' => 'http://www.beijaflore.com/', 'img' => @asset("img/sponsors/beijaflore.png"), 'alt' => 'Beijaflore' ];
                    $sponsors[] = [ 'link' => 'http://www.yves-damonte.fr', 'img' => @asset("img/sponsors/damonte.png"), 'alt' => 'Damonte Immobilier' ];
                    $sponsors[] = [ 'link' => 'http://www.memphis-coffee.com/memphis-coffee-troyes', 'img' => @asset("img/sponsors/memphis.png"), 'alt' => 'Memphis Coffe' ];
                    $sponsors[] = [ 'link' => 'http://www.mgel.fr/', 'img' => @asset("img/sponsors/mgel.png"), 'alt' => 'MGEL' ];
                    $sponsors[] = [ 'link' => 'http://www.auto-ecole-popeye.fr/', 'img' => @asset("img/sponsors/popeye.png"), 'alt' => 'Popeye auto-école' ];
                    shuffle($sponsors);
                ?>
                @foreach($sponsors as $sponsor)
                    <a href="{{{ $sponsor['link'] }}}"><img src="{{{ $sponsor['img'] }}}" alt="{{{ $sponsor['alt'] }}}" /></a>
                @endforeach
            </div>
        </div>
    </div>
@endsection
