@extends('emails.master_layout')

@section('title')
Rentrée UTT
@endsection

@section('content')
    <div style="text-align:left;">
        <span style="font-size:30px">Bonjour {{ $user->first_name }} !</span>
        <p>Demain c’est ta rentrée !</p>
        <p>Et qui dit rentrée dit découverte de plein de nouvelles choses !</p>

        <p>Alors pour t’aider voila un petit planning récap de tous les événements à venir pour ces deux premières semaines.</p>

        <div style="text-align:center; margin: 15px;">
            <a href="{{ asset('/docs/tc.pdf') }}" target="_blank" style="background-color: #00c0ef;border-color: #00acd6;border-radius: 3px;color: #fff;padding: 10px 16px;text-decoration: none;font-size: 18px;line-height: 1.3333333;">Voir mon planning</a><br/>
        </div>
        <p>Comme tu es dans l’équipe {{ $user->team->safe_name ? $user->team->safe_name : $user->team->name }}, ton inscription administrative aura lieu <strong>
    @if($user->team_id == 1)
        lundi à 14h
    @elseif($user->team_id == 2)
        lundi à 14h25
    @elseif($user->team_id == 3)
        lundi à 14h50
    @elseif($user->team_id == 4)
        lundi à 15h15
    @elseif($user->team_id == 5)
        lundi à 15h40
    @elseif($user->team_id == 6)
        lundi à 16h05
    @elseif($user->team_id == 10)
        lundi à 16h30
    @elseif($user->team_id == 7)
        mardi à 9h
    @elseif($user->team_id == 8)
        mardi à 9h30
    @elseif($user->team_id == 9)
        mardi à 10h
    @elseif($user->team_id == 17)
        mercredi à 9h
    @elseif($user->team_id == 18)
        mercredi à 9h25
    @elseif($user->team_id == 19)
        mercredi à 9h50
    @elseif($user->team_id == 22)
        mercredi à 10h15
    @elseif($user->team_id == 23)
        mercredi à 10h40
    @elseif($user->team_id == 14)
        mercredi à 11h05
    @elseif($user->team_id == 13)
        mercredi à 11h30
    @endif
         en P203</strong>.</p>

        <p>On se donne donc rendez-vous, dès lundi 7h45 devant l'accueil de l’utt, pour un petit déjeuner offert par le BDE !</p>
    </div>
</td></tr></table>


<table style="max-width: 600px; width:100%; margin: 15px auto 0 auto; border-collapse: collapse; background-color: #fff; box-shadow: 0 1px 1px rgba(0,0,0,0,1);border-radius: 3px;border-bottom: 3px solid #d2d6de;">
<tr><td style="color: #fff;background-color: #00c0ef;padding: 16px 16px 10px 16px;font-size:20px;font-weight:bold;text-align:center;">
    Passe ton permis !
</td></tr><tr><td style="padding: 10px;">
    <p>
        Parce que le bus c'est pas le plus pratique, et que le vélo sous la pluie, ça mouille, l'auto-école Popeye propose 50€ de réduction pour les cotisants BDE UTT sur le permis de conduire.
    </p>
    <p>
        Si tu l'as déjà, profites en pour passer le permis moto ou remorque !
    </p>
    <div style="text-align:center; margin: 15px;">
        <a href="http://www.popeye-troyes.fr" target="_blank"><img src="{{ asset('img/sponsors/deals/popeye_resultat.png') }}" class="img-thumbnail" /></a>
    </div>

@endsection
