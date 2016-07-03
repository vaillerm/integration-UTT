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
@endsection
