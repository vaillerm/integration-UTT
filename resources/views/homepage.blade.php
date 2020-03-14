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
    @if (Session::get('success'))
        <div class="alert alert-success">
            <p>{{ Session::get('success') }}</p>
        </div>
    @elseif (Session::get('warning'))
        <div class="alert alert-warning">
            <p>{{ Session::get('warning') }}</p>
        </div>
    @elseif (Session::get('error'))
        <div class="alert alert-danger">
            <p>{{ Session::get('error') }}</p>
        </div>
    @endif

    <div class="container">
        <div class="row">
            <div class="text-center">
                <a href="{{ route('newcomer.auth.login') }}" class="btn btn-primary">Je suis nouveau !</a><br/>
                <a href="{{ route('menu') }}" class="btn btn-default">Je suis étudiant à l'UTT</a>
                    {{--@if(((new DateTime(Config::get('services.wei.registrationStart')))->diff(new DateTime()))->invert)
                        <p>Ouverture des inscriptions pour le weekend dans</p>
                        <div class="countdown hidden-xs" style="width:640px;margin:20px auto;"></div>
                        <p><big class="visible-xs">{{ ((new DateTime(Config::get('services.wei.registrationStart')))->diff(new DateTime()))->format('%d jrs %h hrs %i min et %s sec') }}</big></p>
                    @else
                        <p>Inscriptions pour le weekend ouvertes !</p>
                    @endif
                    --}}
                <br/><br/>
	    </div>
        </div>
    </div>

    <div class="row sponsor">
        <div class="text-center">
            <?php
                $sponsors = [];
                //$sponsors[] = [ 'link' => 'http://www.ada.fr/location-voiture-troyes.html', 'img' => asset("img/sponsors/ada.png"), 'alt' => 'ADA Location de véhicules' ];
                //$sponsors[] = [ 'link' => 'http://www.yves-damonte.fr', 'img' => asset("img/sponsors/damonte.png"), 'alt' => 'Damonte Immobilier' ];
                //$sponsors[] = [ 'link' => 'http://www.mgel.fr/', 'img' => asset("img/sponsors/mgel.png"), 'alt' => 'MGEL' ];
                //$sponsors[] = [ 'link' => 'http://www.auto-ecole-popeye.fr/', 'img' => asset("img/sponsors/popeye-white-background.png"), 'alt' => 'Popeye auto-école' ];
                //$sponsors[] = [ 'link' => 'https://www.beijaflore.com/fr/', 'img' => asset("img/sponsors/beijaflore-2019.png"), 'alt' => 'Beijaflore' ];
                shuffle($sponsors);
            ?>
            @foreach($sponsors as $sponsor)
                <a href="{{{ $sponsor['link'] }}}"><img src="{{{ $sponsor['img'] }}}" alt="{{{ $sponsor['alt'] }}}" /></a>
            @endforeach
        </div>
    </div>
@endsection
