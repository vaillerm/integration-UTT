@extends('layouts.master')

@section('title')
Accueil
@endsection

@section('css')
<link rel="stylesheet" href="{{ asset('css/bootstrap.min.css') }}" media="screen">
<link rel="stylesheet" href="{{ asset('css/homepage.css') }}">
<link rel="stylesheet" href="{{ asset('css/flipclock.css') }}">
@endsection

@section('js')
    <script src="{{ asset('js/flipclock.min.js') }}"></script>
    <script>
    var countdown = $('.countdown').FlipClock({{ (new DateTime(Config::get('services.wei.registrationStart')))->getTimestamp() - (new DateTime())->getTimestamp() }}, {
        countdown: false,
		clockFace: 'DailyCounter',
		language: 'french',
    });
    </script>
@endsection

@section('bodycontent')

    <div class="container">
        <div class="row">
            <div class="text-center">
                <h1>Bienvenue sur le site de l'Intégration</h1>
		<br/><br/>

                <a href="{{ route('newcomer.auth.login') }}" class="btn btn-primary">Je suis nouveau !</a><br/>
                <a href="{{ route('menu') }}" class="btn btn-default">Je suis étudiant à l'UTT</a>

                <br/><br/>
                                
                    @if(((new DateTime(Config::get('services.wei.registrationStart')))->diff(new DateTime()))->invert)
                        <p>Ouverture des inscriptions pour le weekend dans</p>
                        <div class="countdown hidden-xs" style="width:640px;margin:20px auto;"></div>
                        <p><big class="visible-xs">{{ ((new DateTime(Config::get('services.wei.registrationStart')))->diff(new DateTime()))->format('%d jrs %h hrs %i min et %s sec') }}</big></p>
                    @else
                        <p>Inscriptions pour le weekend ouvertes !</p>
                    @endif
                
                <br/><br/>
	    </div>
        </div>

        <div class="row sponsor">
            <div class="text-center">
                <?php
                    $sponsors = [];
                    $sponsors[] = [ 'link' => 'http://www.accesbureautique.fr/', 'img' => asset("img/sponsors/access.png"), 'alt' => 'Access Bureautique' ];
                    $sponsors[] = [ 'link' => 'http://www.ada.fr/location-voiture-troyes.html', 'img' => asset("img/sponsors/ada.png"), 'alt' => 'ADA Location de véhicules' ];
                    $sponsors[] = [ 'link' => 'http://www.beijaflore.com/', 'img' => asset("img/sponsors/beijaflore.png"), 'alt' => 'Beijaflore' ];
                    $sponsors[] = [ 'link' => 'http://www.yves-damonte.fr', 'img' => asset("img/sponsors/damonte.png"), 'alt' => 'Damonte Immobilier' ];
                    $sponsors[] = [ 'link' => 'http://www.memphis-coffee.com/memphis-coffee-troyes', 'img' => asset("img/sponsors/memphis.png"), 'alt' => 'Memphis Coffe' ];
                    $sponsors[] = [ 'link' => 'http://www.mgel.fr/', 'img' => asset("img/sponsors/mgel.png"), 'alt' => 'MGEL' ];
                    $sponsors[] = [ 'link' => 'http://www.auto-ecole-popeye.fr/', 'img' => asset("img/sponsors/popeye.png"), 'alt' => 'Popeye auto-école' ];
                    shuffle($sponsors);
                ?>
                @foreach($sponsors as $sponsor)
                    <a href="{{{ $sponsor['link'] }}}"><img src="{{{ $sponsor['img'] }}}" alt="{{{ $sponsor['alt'] }}}" /></a>
                @endforeach
            </div>
        </div>
    </div>
@endsection