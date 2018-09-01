@extends('layouts.email')

@section('title')
Your integration team / Ton équipe d'intégration
@endsection

@section('content')
    <div style="text-align:left;">
        <span style="font-size:30px">Hi {{ $user->first_name }} !</span>
	<p> Welcome to UTT ! It's time for us to talk about what's going to happen at the beginning of the semester.</p>
        <p>The integration includes several activities related to :
        <div style="text-align:center; margin: 15px;font-size:20px;font-weight:bold;">
            {{ Config::get('services.theme.english') }}
        </div>

	<p>
	  {{ Config::get('services.ce.maxTeamTc')+Config::get('services.ce.maxTeamBranch') }} teams will be divided into <strong>two factions</strong> and will confront themselves during the week :
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
    You are on the team
<em>{{ $user->team->safeName ?? $user->team->name }}</em> !
</td></tr><tr><td style="padding: 10px;">
    	<p style="margin-top:0;">
	Your team is made up of new students and {{ $user->team->ce->count() }} team leaders. They'll be with you throughout the whole week, during the different games and activities.
	</p>
    <img src="{{ asset('/uploads/teams-logo/'.$user->team->id.'.'.$user->team->img) }}" alt="Equipe" style="float:left;width:140px;"/>
    <div style="margin-bottom:5px;margin-left:155px;line-height:26px; font-size: 15px">
        Your team is <strong>{{ $user->team->safeName ?? $user->team->name }}</strong><br/>
        Your faction is <strong>{{ $user->team->faction->name }}</strong><br/>
        @if(substr($user->team->facebook, 0, 4) == 'http')
            You can join them on their <a href="{{ $user->team->facebook }}" style="color: #3c8dbc;" target="_blank">Facebook group</a>
        @endif
    </div>
    <div style="clear:both"></div>

    <h3>But we are going to let them introduce themselves ! ! (in French)</h3>
    <p style="text-align:justify"><em>{!! nl2br(e($user->team->description)) !!}</em></p>

    @if(substr($user->team->facebook, 0, 4) == 'http')
        <div style="text-align:center; margin: 15px;">
            <a href="{{ $user->team->facebook }}" target="_blank" style="background-color: #00c0ef;border-color: #00acd6;border-radius: 3px;color: #fff;padding: 10px 16px;text-decoration: none;font-size: 18px;line-height: 1.3333333;">Join them on facebook !</a><br/>
        </div>
    @endif
@endif


</td></tr></table>

<table style="max-width: 600px; width:100%; margin: 15px auto 0 auto; border-collapse: collapse; background-color: #fff; box-shadow: 0 1px 1px rgba(0,0,0,0,1);border-radius: 3px;border-bottom: 3px solid #d2d6de;">
<tr><td style="color: #fff;background-color: #00c0ef;padding: 16px 16px 10px 16px;font-size:20px;font-weight:bold;text-align:center;">
Planning
</td></tr><tr><td style="padding: 10px;">
<p>
Here is the planning of all the activities for the next 2 weeks. If you have any question, just send us a message !
<div style="text-align:center; margin-top: 30px;">
    <a href="{{ asset('/docs/foreigners.pdf') }}" target="_blank" style="background-color: #00c0ef;border-color: #00acd6;border-radius: 3px;color: #fff;padding: 10px 16px;text-decoration: none;font-size: 18px;line-height: 1.3333333;">Your planning</a><br/>
</div>
</p>

</td></tr></table>


<table style="max-width: 600px; width:100%; margin: 15px auto 0 auto; border-collapse: collapse; background-color: #fff; box-shadow: 0 1px 1px rgba(0,0,0,0,1);border-radius: 3px;border-bottom: 3px solid #d2d6de;">
<tr><td style="color: #fff;background-color: #00c0ef;padding: 16px 16px 10px 16px;font-size:20px;font-weight:bold;text-align:center;">
Weekend
</td></tr><tr><td style="padding: 10px;">
<p>The integration weekend is coming !</p>
<p>You will have the chance to dance all night, eat some meals made with love and engage in many outdoors activities !</p>
<p>For the weekend :</p>
<ul>
    <li>The price is 55€ all inclusive (meals, transport)</li>
    <li>There is also a 60€ deposit that will be refunded if everything goes well during the weekend !</li>
    <li>3€25 if you want to take the sandwich offered before the bus friday, otherwise please take a sandwich.</li>
</ul>

{{-- <div style="text-align:center; margin: 15px;">
    <a href="https://integration.utt.fr" target="_blank" style="background-color: #00c0ef;border-color: #00acd6;border-radius: 3px;color: #fff;padding: 10px 16px;text-decoration: none;font-size: 18px;line-height: 1.3333333;">Register on integration.utt.fr</a><br/>
</div> --}}

<p>You will receive your password and login really soon, BE CAREFUL the website is in French. If you don’t understand please come monday at 14h or wednesday at 14h in the Hall N at the “Zeshop” stand and Tristan will help you. DO NOT go to his office, come at those hours at the indicated place.</p>

</td></tr></table>

<hr/>

<table style="max-width: 600px; width:100%; margin: 15px auto 0 auto; border-collapse: collapse; background-color: #fff; box-shadow: 0 1px 1px rgba(0,0,0,0,1);border-radius: 3px;border-bottom: 3px solid #d2d6de;">
<tr><td style="color: #fff;background-color: #00c0ef;padding: 16px 16px 10px 16px;font-size:20px;font-weight:bold;text-align:center;">
    Bienvenue !
</td></tr><tr><td style="padding: 10px;">

    <div style="text-align:left;">
        <span style="font-size:30px">Salut {{ $user->first_name }} !</span>
	<p>Bienvenue à l'UTT ! Il est temps de te parler de ce qui va se passer à la rentrée.</p>
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
Planning
</td></tr><tr><td style="padding: 10px;">
<p>
Voila un planning des activités prévues pour les 2 semaines d’intégrations. Si vous avez des questions n’hésitez pas à nous envoyer un message !
<div style="text-align:center; margin-top: 30px;">
    <a href="{{ asset('/docs/foreigners.pdf') }}" target="_blank" style="background-color: #00c0ef;border-color: #00acd6;border-radius: 3px;color: #fff;padding: 10px 16px;text-decoration: none;font-size: 18px;line-height: 1.3333333;">Ton planning</a><br/>
</div>
</p>

</td></tr></table>


<table style="max-width: 600px; width:100%; margin: 15px auto 0 auto; border-collapse: collapse; background-color: #fff; box-shadow: 0 1px 1px rgba(0,0,0,0,1);border-radius: 3px;border-bottom: 3px solid #d2d6de;">
<tr><td style="color: #fff;background-color: #00c0ef;padding: 16px 16px 10px 16px;font-size:20px;font-weight:bold;text-align:center;">
Weekend
</td></tr><tr><td style="padding: 10px;">
<p>Le weekend d’intégration approche !</p>
<p>Vous aurez la chance de danser toute la nuit, manger des repas préparé avec amour et participer à des activités diverses et variées en plein air !</p>
<p>Pour le weekend :</p>
<ul>
    <li>Le prix est de 55€ tout compris (repas, transport)</li>
    <li>60€ de caution qui vous sera rendu si tout se passe bien pendant le weekend !</li>
    <li>3€25 si vous voulez prendre le repas prévu avant le bus du vendredi, sinon amenez à manger.</li>
</ul>

{{-- <div style="text-align:center; margin: 15px;">
    <a href="https://integration.utt.fr" target="_blank" style="background-color: #00c0ef;border-color: #00acd6;border-radius: 3px;color: #fff;padding: 10px 16px;text-decoration: none;font-size: 18px;line-height: 1.3333333;">Inscrit toi integration.utt.fr</a><br/>
</div> --}}

<p>Vous allez recevoir votre mot de passe et login très rapidement, ATTENTION le site internet est en Francais. Si vous ne comprenez pas, merci de venir lundi à 14h ou mercredi à 14h dans le Hall N au stand “Zeshop” et Tristan vous aidera. NE PAS ALLER à son bureau, merci de venir aux heures et endroit indiqué.</p>


@endsection
