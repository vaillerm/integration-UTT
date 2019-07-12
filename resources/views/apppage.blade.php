@extends('layouts.master')

@section('title')
Application
@endsection

@section('css')
<link rel="stylesheet" href="{{ asset('css/bootstrap.min.css') }}" media="screen">
<link rel="stylesheet" href="{{ asset('css/app.css') }}">
@endsection

@section('js')
    <script src="{{ asset('js/app.js') }}"></script>
@endsection

@section('bodycontent')

    <div class="container">
        <div class="row">
            <div class="text-center">
                <h1>Scanner le QRCode suivant pour télécharger l'application</h1>
                <img src="{{ asset('/img/qrcode.png') }}" alt="QRCode" class="qrcode" />
                <div class="store-buttons">
                    <a href='https://play.google.com/store/apps/details?id=fr.utt.ung.integration&hl=fr_FR&pcampaignid=MKT-Other-global-all-co-prtnr-py-PartBadge-Mar2515-1'><img alt='Disponible sur Google Play' src='https://play.google.com/intl/en_us/badges/images/generic/fr_badge_web_generic.png'/></a>
                    <a href='https://apps.apple.com/us/app/int%C3%A9gration-utt/id1403064675?l=fr&ls=1'><img alt='Disponible sur Apple Store' src='{{ asset('/img/ios.png') }}'/></a>
                </div>
            </div>
        </div>
    </div>

@endsection
