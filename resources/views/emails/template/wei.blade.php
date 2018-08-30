@extends('layouts.email')

@section('title')
Inscription au weekend ouvertes
@endsection

@section('content')
    <div style="text-align:left;">
        <span style="font-size:30px">Salut {{ $user->first_name }} !</span>
        <p>On espère que tu es prêt à vivre une semaine incroyable à nos côtés ! Comme vous avez pu le voir l’inscription au Week-end d’Intégration est désormais ouverte !</p>
        <p>Le départ de ce fameux week-end se fera vendredi après manger, on te conseille de prendre le repas fourni par le Crous, au modique prix de 3€25 (un repas au restaurant universitaire) afin de te rassasier avant le grand départ !</p>

        <div style="text-align:center; margin: 15px;">
            <a href="https://integration.utt.fr" target="_blank" style="background-color: #00c0ef;border-color: #00acd6;border-radius: 3px;color: #fff;padding: 10px 16px;text-decoration: none;font-size: 18px;line-height: 1.3333333;">Inscription sur integration.utt.fr</a><br/>
        </div>

        <p>Concernant le matériel à emporter n’oublie surtout pas :</p>
        <ul>
            <li>Un sac de couchage chaud</li>
            <li>Ton déguisement</li>
            <li>Des vêtements qui ne craignent rien</li>
            <li>Des vêtements qui tiennent chaud</li>
            <li>Un k-way</li>
            <li>Ton autorisation parentale si tu es mineur</li>
            <li>Et un ananas (primordial)</li>
        </ul>
    </div>

</td></tr></table>
<table style="max-width: 600px; width:100%; margin: 15px auto 0 auto; border-collapse: collapse; background-color: #fff; box-shadow: 0 1px 1px rgba(0,0,0,0,1);border-radius: 3px;border-bottom: 3px solid #d2d6de;">
<tr><td style="color: #fff;background-color: #00c0ef;padding: 16px 16px 10px 16px;font-size:20px;font-weight:bold;text-align:center;">
    Le N'UTT : Le journal de l'UTT
</td></tr><tr><td style="padding: 10px;">
    <p>Le N'UTT, c'est le club étudiant de l'UTT qui édite le journal de l'école, tu pourras le retrouver de temps en temps au foyer. Une version spéciale intégration a été concocté pour toi !</p>
    <div style="text-align:center; margin: 15px;">
        <img src="{{ asset('img/mails/wei/nutt.png') }}" style="width: 80%;margin-bottom:15px;" /><br/>
        <a href="{{ asset('docs/nutt.pdf') }}" target="_blank" style="background-color: #00c0ef;border-color: #00acd6;border-radius: 3px;color: #fff;padding: 10px 16px;text-decoration: none;font-size: 18px;line-height: 1.3333333;">Lire le N'UTT</a><br/>
    </div>


</td></tr></table>

<table style="max-width: 600px; width:100%; margin: 15px auto 0 auto; border-collapse: collapse; background-color: #fff; box-shadow: 0 1px 1px rgba(0,0,0,0,1);border-radius: 3px;border-bottom: 3px solid #d2d6de;">
<tr><td style="color: #fff;background-color: #00c0ef;padding: 16px 16px 10px 16px;font-size:20px;font-weight:bold;text-align:center;">
    Un camion à prix réduit
</td></tr><tr><td style="padding: 10px;">
    <p>
        Pour déménager, un camion c'est toujours utile. On te conseille de passer par ADA, notre partenaire pour bénéficier de 20% de réduction sur ta location.
    </p>

    <div style="text-align:center;">
        <a href="https://www.ada.fr/location-voiture-troyes.html" target="_blank">
            <img src="{{ asset('img/sponsors/ada.png') }}" class="img-thumbnail hidden-xs" style="width:150px;"/>
        </a>
    </div>


@endsection
