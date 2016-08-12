@extends('layouts.newcomer')

@section('css')
		<link rel="stylesheet" href="{{ @asset('css/flipclock.css') }}">
@endsection

@section('js')
    <script src="{{ @asset('js/flipclock.min.js') }}"></script>
    <script>
    var countdown = $('.countdown').FlipClock({{ (new DateTime(Config::get('services.wei.registrationStart')))->getTimestamp() - (new DateTime())->getTimestamp() }}, {
        countdown: true,
		clockFace: 'DailyCounter',
		language: 'french',
    });
    </script>
@endsection

@section('title')
Inscription au WEI
@endsection

@section('smalltitle')
Le Week End d'Intégration
@endsection

@section('content')
    <div class="box box-default">
        <div class="box-header with-border">
            <h3 class="box-title">Ouverture des inscriptions pour le weekend dans ...</h3>
        </div>
        <div class="box-body text-center">
            <div class="countdown hidden-xs" style="width:640px;margin:20px auto;"></div>
			<big class="visible-xs">{{ ((new DateTime(Config::get('services.wei.registrationStart')))->diff(new DateTime()))->format('%d jours %h heures %i minutes et %s secondes') }}</big>
        </div>
    </div>
@endsection
