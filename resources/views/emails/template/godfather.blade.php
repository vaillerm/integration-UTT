@extends('layouts.email')

@section('title')
Bienvenue à l'UTT
@endsection

@section('content')
    <div style="text-align:left;">
        <span style="font-size:30px">Salut {{ $user->first_name }} !</span>
        <p>Bravo pour ton acceptation à l'UTT !</p>
        <p>Nous sommes l'équipe d'intégration, des étudiants bénévoles qui préparent minutieusement ton arrivée pour que celle-ci reste inoubliable.</p>

        <p>Animations, soirées, jeux... Un tas d'événements incroyables t'attendent dès le <strong>3&nbsp;septembre</strong> et ce, durant toute la semaine, jusqu'au Week-End d'Intégration.
        Tout est fait pour que tu t'éclates et que tu fasses des rencontres.</p>

        <div style="text-align:center">
            <img src="https://gallery.mailchimp.com/386cd6423d5cfa3d4a83232e8/images/c8c9fda9-e243-4aba-aaaa-422dd02f8011.png" alt="Intégration UTT" />
        </div>
    </div>
</td></tr></table>


<table style="max-width: 600px; width:100%; margin: 15px auto 0 auto; border-collapse: collapse; background-color: #fff; box-shadow: 0 1px 1px rgba(0,0,0,0,1);border-radius: 3px;border-bottom: 3px solid #d2d6de;">
<tr><td style="color: #fff;background-color: #00c0ef;padding: 16px 16px 10px 16px;font-size:20px;font-weight:bold;text-align:center;">
    Site d'intégration
</td></tr><tr><td style="padding: 10px;">

        <p>Tu peux d'ores et déjà te connecter au site d'intégration pour retrouver toutes les informations sur ta rentrée : parrainage, équipe d'inté et inscription au weekend...</p>
        <p>Pour te connecter, on va te demander tes identifiants, tu les as déjà reçus dans un email envoyé par l'UTT. Ce sont les mêmes que sur le site des admissions <a target="_blank" href="http://admission.utt.fr/" style="color: #3c8dbc;">admission.utt.fr</a> et l’UT3L <a target="_blank" href="https://ut3l.utt.fr/" style="color: #3c8dbc;">ut3l.utt.fr</a>.</p>
        <div style="text-align:center; margin: 15px;">
            <a target="_blank" href="https://integration.utt.fr" style="background-color: #00c0ef;border-color: #00acd6;border-radius: 3px;color: #fff;padding: 10px 16px;text-decoration: none;font-size: 18px;line-height: 1.3333333;">integration.utt.fr</a><br/>
            <span style="font-style: italic;display:block;margin-top: 15px;">Ton identifiant : {{ $user->login }}</span>
        </div>


@if ($user->godFather)
</td></tr></table>

<table style="max-width: 600px; width:100%; margin: 15px auto 0 auto; border-collapse: collapse; background-color: #fff; box-shadow: 0 1px 1px rgba(0,0,0,0,1);border-radius: 3px;border-bottom: 3px solid #d2d6de;">
<tr><td style="color: #fff;background-color: #00c0ef;padding: 16px 16px 10px 16px;font-size:20px;font-weight:bold;text-align:center;">
    {{ $user->godFather->first_name }} {{ $user->godFather->last_name }}, {{ ($user->godFather->sex)?'ta marraine':'ton parrain' }} !
</td></tr><tr><td style="padding: 10px;">
    <h2 style="margin-top:0;margin-bottom: 5px;"></h2>
    <p style="margin-top:0;">Lorsque tu arrives à l'UTT, un étudiant plus ancien devient ton parrain ou ta marraine.
        Cet étudiant sera ton contact privilégié pour découvrir l'école, mais aussi la vie étudiante&nbsp;troyenne.
        Il pourra répondre à toutes tes questions, que ce soit sur l’UTT, les logements, les cours, la vie à Troyes...
    </p>

    <img src="{{ asset('/uploads/students-trombi/'.$user->godFather->student_id.'.jpg') }}" alt="Photo" style="float:left;width:100px;"/>
    <div style="margin-bottom:5px;margin-left:115px;line-height:26px; font-size: 15px">
        <span style="margin-right: 5px; font-size:20px;vertical-align:bottom">📞</span> {{ $user->godFather->phone }}<br/>
        <span style="margin-right: 5px; font-size:20px;vertical-align:bottom">📧</span> {{ $user->godFather->email }}<br/>
        <span style="margin-right: 5px; font-size:20px;vertical-align:bottom">🚀</span> {{ ($user->godFather->sex)?'Elle':'Il' }}
        vient de {{ $user->godFather->city }} en {{ $user->godFather->country }}<br/>
        @if ($user->godFather->facebook)
            <span style="margin-right: 5px; font-size:20px;vertical-align:bottom">💬</span> <a style="color: #3c8dbc;" target="_blank" href="{{ $user->godFather->facebook }}">Profil Facebook</a><br/>
        @endif
        @if ($user->godFather->surname)
            <span style="margin-right: 5px; font-size:20px;vertical-align:bottom">👋</span> On {{ ($user->godFather->sex)?'la':'le' }} surnomme <em>{{$user->godFather->surname}}</em>
        @endif
    </div>
    <div style="clear:both"></div>

    <h3>{{ ($user->godFather->sex)?'Elle':'Il' }} a un message pour toi !</h3>
    <p style="text-align:justify;font-size:1.1em"><em>{!! nl2br(e($user->godFather->referral_text)) !!}</em></p>

    <div style="text-align:center; margin: 15px;">
        <a href="https://integration.utt.fr" target="_blank" style="background-color: #00c0ef;border-color: #00acd6;border-radius: 3px;color: #fff;padding: 10px 16px;text-decoration: none;font-size: 18px;line-height: 1.3333333;">Lui envoyer mes coordonées</a><br/>
        <span style="font-style: italic;display:block;margin-top: 15px;">{{ ($user->godFather->sex)?'Elle':'Il' }} n'a pas tes coordonnées, c'est à toi de faire le premier pas ;)</span>
    </div>
@endif


</td></tr></table>

<table style="max-width: 600px; width:100%; margin: 15px auto 0 auto; border-collapse: collapse; background-color: #fff; box-shadow: 0 1px 1px rgba(0,0,0,0,1);border-radius: 3px;border-bottom: 3px solid #d2d6de;">
<tr><td style="color: #fff;background-color: #00c0ef;padding: 16px 16px 10px 16px;font-size:20px;font-weight:bold;text-align:center;">
    Tes assurances pour ton arrivée à l'UTT
</td></tr><tr><td style="padding: 10px;">
    <p style="margin-top:0;">En arrivant dans une nouvelle école et dans une nouvelle ville, tu vas avoir besoin d'assurances : assurance responsabilité civile (obligatoire pour s'inscrire à l'UTT) et assurance logement. La MGEL, notre partenaire, te propose toutes les assurances nécéssaires à ta vie étudiante ; pour y souscrire, une seule adresse : <a href="https://bde.utt.fr/assurances" target="_blank" style="color: #3c8dbc;">bde.utt.fr/assurances</a>.</p>
    <p><em>PS: En passant par ce lien, tu donnes un petit coup de pouce à l'Intégration pour organiser encore plus de folies ;)</em></p>
    <div style="text-align:center; margin: 15px;">
        <a href="https://bde.utt.fr/assurances-integration" target="_blank" style="background-color: #00c0ef;border-color: #00acd6;border-radius: 3px;color: #fff;padding: 10px 16px;text-decoration: none;font-size: 18px;line-height: 1.3333333;">bde.utt.fr/assurances</a><br/>
    </div>

    </div>
@endsection
