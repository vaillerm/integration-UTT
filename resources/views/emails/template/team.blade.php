@extends('layouts.email')

@section('title')
Ton équipe d'intégration
@endsection

@section('content')
    <div style="text-align:left;">
        <span style="font-size:30px">Salut {{ $user->first_name }} !</span>
        <p>Nous t'avons déjà présenter ton parrain dans un précédent email, il est donc temps de te parler un peu plus de ce qui va se passer à la rentrée.</p>
        <p>L'intégration comportes différentes activités dont le thème cette année est :
        <div style="text-align:center; margin: 15px;font-size:20px;font-weight:bold;">
            {{ Config::get('services.theme') }}
        </div>
        <p>
            Sur ce thème, {{ Config::get('services.ce.maxTeamTc')+Config::get('services.ce.maxTeamBranch') }} équipes, réparties dans <strong>deux factions</strong> vont s'affronter tout au long de la semaine :
        </p>
        <div style="text-align:center;">
            <img src="{{ asset('/img/mails/teams/BadGuys.png') }}" alt="Bad Guys" style="width:45%"/>
            <img src="{{ asset('/img/mails/teams/GoodGuys.png') }}" alt="Good Guys" style="width:45%"/>
        </div>
    </div>

@if ($user->team)
</td></tr></table>

<table style="max-width: 600px; width:100%; margin: 15px auto 0 auto; border-collapse: collapse; background-color: #fff; box-shadow: 0 1px 1px rgba(0,0,0,0,1);border-radius: 3px;border-bottom: 3px solid #d2d6de;">
<tr><td style="color: #fff;background-color: #00c0ef;padding: 16px 16px 10px 16px;font-size:20px;font-weight:bold;text-align:center;">
    Tu est dans l'équipe <em>{{ $user->team->safeName ?? $user->team->name }}</em> !
</td></tr><tr><td style="padding: 10px;">
    <p style="margin-top:0;">
        Ton équipe est composé de nouveaux et de {{ $user->team->ce->count() }} chefs d'équipes.
        Ils t'accompagneront pendant toute ta semaine d'intégration, au cours des différents jeux et activités te seront proposés.
    </p>

    <img src="{{ asset('/uploads/teams-logo/'.$user->team->id.'.'.$user->team->img) }}" alt="Equipe" style="float:left;width:140px;"/>
    <div style="margin-bottom:5px;margin-left:155px;line-height:26px; font-size: 15px">
        Ton équipe s'appelle <strong>{{ $user->team->safeName ?? $user->team->name }}</strong><br/>
        Elle est dans la faction <strong>{{ $user->team->faction->name }}</strong><br/>
        @if(substr($user->team->facebook, 0, 4) == 'http')
            Rejoins ton équipe sur le <a href="{{ $user->team->facebook }}" style="color: #3c8dbc;" target="_blank">groupe Facebook</a>
        @endif
    </div>
    <div style="clear:both"></div>

    <h3>Mais laissons-les se présenter !</h3>
    <p style="text-align:justify"><em>{!! nl2br(e($user->team->description)) !!}</em></p>

    @if(substr($user->team->facebook, 0, 4) == 'http')
        <div style="text-align:center; margin: 15px;">
            <a href="{{ $user->team->facebook }}" target="_blank" style="background-color: #00c0ef;border-color: #00acd6;border-radius: 3px;color: #fff;padding: 10px 16px;text-decoration: none;font-size: 18px;line-height: 1.3333333;">Rejoins les sur Facebook !</a><br/>
        </div>
        @endif
@endif


</td></tr></table>

<table style="max-width: 600px; width:100%; margin: 15px auto 0 auto; border-collapse: collapse; background-color: #fff; box-shadow: 0 1px 1px rgba(0,0,0,0,1);border-radius: 3px;border-bottom: 3px solid #d2d6de;">
<tr><td style="color: #fff;background-color: #00c0ef;padding: 16px 16px 10px 16px;font-size:20px;font-weight:bold;text-align:center;">
    Site d'Inté
</td></tr><tr><td style="padding: 10px;">
    <p>Retrouve toutes les autres infos sur l'intégration sur notre site !</p>
    <div style="text-align:center; margin: 15px;">
        <a href="https://integration.utt.fr" target="_blank" style="background-color: #00c0ef;border-color: #00acd6;border-radius: 3px;color: #fff;padding: 10px 16px;text-decoration: none;font-size: 18px;line-height: 1.3333333;">integration.utt.fr</a><br/>
    </div>
    <p><em>Et si tu n'arrives pas à te connecter ou pour toute autre question, <a href="{{ route('contact') }}" style="color: #3c8dbc;" target="_blank">envoie nous un message</a>.</em></p>

</td></tr></table>

<table style="max-width: 600px; width:100%; margin: 15px auto 0 auto; border-collapse: collapse; background-color: #fff; box-shadow: 0 1px 1px rgba(0,0,0,0,1);border-radius: 3px;border-bottom: 3px solid #d2d6de;">
<tr><td style="color: #fff;background-color: #00c0ef;padding: 16px 16px 10px 16px;font-size:20px;font-weight:bold;text-align:center;">
    Ton appartement étudiant sur troyes
</td></tr><tr><td style="padding: 10px;">
    <p>Tu cherches un appartement en résidence étudiante, meublé, près de l'UTT ou plutôt en centre ville ? Damonte Immobilier propose forcément ce que tu cherches.</p>
    <p>Pour voir ce qu'ils proposent, une seule adresse : <a href="http://www.yves-damonte.fr/location-immobiliere-troyes/logement-etudiant/" style="color: #3c8dbc;" target="_blank">yves-damonte.fr</a></p>

    <div style="text-align:center;">
        <a href="http://www.yves-damonte.fr/location-immobiliere-troyes/logement-etudiant/" target="_blank">
            <img src="{{ asset('img/sponsors/damonte/residence.jpg') }}" class="img-thumbnail hidden-xs" style="max-height:120px;"/>
            <img src="{{ asset('img/sponsors/damonte/appart2.jpg') }}" class="img-thumbnail" style="max-height:120px;"/>
            <img src="{{ asset('img/sponsors/damonte.png') }}" class="img-thumbnail" style="padding-bottom:30px;" />
        </a>
    </div>


@endsection
