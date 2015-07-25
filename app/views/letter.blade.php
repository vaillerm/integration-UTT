<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="utf-8">
    <title>Parrainage</title>
    <link rel="stylesheet" href="{{ asset('css/normalize.css') }}">
    <link rel="stylesheet" href="{{ asset('css/letter.css') }}">
</head>

<body>
    <img alt="map" height="792" width="1123" id="map" src="{{ asset('img/letter/map.jpg') }}">
    <img alt="avatar" height="96" width="76" id="avatar" src="{{ asset('referrals/' . $newcomer->referral->student_id . '.jpg') }}">
    <img alt="anneaux" height="96" width="76" id="anneaux" src="{{ asset('img/letter/anneaux.png') }}">
    <h1 id="name">{{ $newcomer->referral->first_name . ' ' . $newcomer->referral->last_name }}</h1>
    <p id="descParrain">
        Cette personne, c'est ton
        <strong>parrain</strong>. Son rôle est de te guider durant toute l'intégration et au delà. Il est conseillé de prendre contact avec lui rapidement :)
    </p>
    <p id="phone">{{ $newcomer->referral->phone }}</p>
    <p id="mail">{{ $newcomer->referral->email }}</p>
    <h1 id="motParrainTitre">Le mot de ton parrain</h1>
    <p id="motParrain">{{{ $newcomer->referral->free_text }}}</p>
    <h2 id="plusInfosTitre">Plus d'infos ?</h2>
    <p id="plusInfos">
        {{ $newcomer->first_name . ' ' . $newcomer->last_name }}, voici ton compte temporaire pour accéder au site étudiant sur <a href="http://etu.utt.fr">etu.utt.fr</a> ainsi qu'au site de l'intégration sur <a href="http://integration.utt.fr">integration.utt.fr</a> :
        <br>
        <span>
            - Identifiant :
            <strong>adm{{ $newcomer->id }}</strong>
            <br> - Mot de passe :
            <strong>{{ $newcomer->password }}</strong>
            <br>
        </span>
    </p>
    <h2 id="teamName">Ton équipe :
        <em>{{ $newcomer->team->name }}</em>
    </h2>
    <p id="teamDesc">
        {{{ $newcomer->team->description }}}
    </p>
    <img alt="teamIcon" id="teamIcon" src="{{ $newcomer->team->img_url }}" height="210" width="180">
</body>

</html>
