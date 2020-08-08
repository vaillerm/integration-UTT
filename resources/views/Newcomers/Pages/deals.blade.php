@extends('layouts.newcomer')

@section('title')
Bon plans
@endsection

@section('smalltitle')
Que l'intégration a trouvé pour toi !
@endsection

@section('content')
    <div class="box box-default">
        <div class="box-header with-border">
            <h3 class="box-title">Les bon plans de l'intégration !</h3>
        </div>
        <div class="box-body">
        <h4> Les partenaires de ton intégration : </h4>

        <ul>
            <li><a href="#damonte">Damonte</a></li>
            <li><a href="#ada">ADA</a></li>
            <li><a href="#lcl">LCL</a></li>
            <li><a href="#popeye">Auto-école Popeye</a></li>
            <li><a href="#mgel">MGEL (Mutuelle Générale de Etudiants de l’Est)</a></li>
        </ul>

        <hr/>
        <p><a href="http://www.yves-damonte.fr"><img src="img/sponsors/deals/damonte_resultat.jpg" style="float:right" class="img-thumbnail" /></a></p>
        <p> <h4 id="damonte"> Damonte </h4> </p>
        <p>C’est LE spécialiste de la location étudiante qui saura être à ton écoute pour trouver l’appartement qu’il te faut ! Tu cherches un appartement en résidence étudiante, ou plutôt en centre ville ? Ils ont forcément ce que tu cherches !</p>
        <p>120 Rue du Général de Gaulle 10000 Troyes</p>
        <p>295 Rue du Faubourg Croncels 10000 Troyes</p>
        <p><a href="http://www.yves-damonte.fr">http://www.yves-damonte.fr</a></p>

        <p><a href="http://www.ada.fr/location-voiture-troyes.html"><img src="img/sponsors/deals/ada_resultat.png" style="float:right" class="img-thumbnail" /></a></p>
        <p> <h4 id="ada"> ADA </h4> </p>
        <p>C’est LE spécialiste en location de véhicules pour les étudiants. Tu pourras bénéficier de 15% de réduction sur ta location.
        Tu viens d’avoir ton permis ?
        Pas de soucis, avec ADA, dès 18 ans, tu peux louer tous les véhicules sans frais supplémentaire !</p>
        <p>32 Rue de la Fontaine Saint Martin 10120 Saint André les Vergers </p>
        <p><a href="http://www.ada.fr/location-voiture-troyes.html">http://www.ada.fr/location-voiture-troyes.html</a></p>

        <p><a href="http://www.lcl.fr"><img src="img/sponsors/deals/lcl_resultat.jpg" style="float:right" class="img-thumbnail" /></a></p>
        <p> <h4 id="lcl"> LCL </h4> </p>
        <p>LCL t’offrira 55€ à l’ouverture d’un compte chez eux (ils t’offrent le WE d’inté). Tu pourras aussi bénéficier d’une carte bancaire pour seulement 1€ par mois. En cas d’imprévu, un découvert de 400€ te sera autorisé. Enfin, tu pourras bénéficier d’un emprunt au taux exceptionnel de 1%.</p>
        <p>Tu pourras même ouvrir ton compte à l’UTT le mardi après-midi !</p>
        <p>Il te suffit de te munir :</p>
        <ul type="circle">
            <li>d’un justificatif d’identité (carte d’identité, passeport, carte de séjour) ;</li>
            <li>d’un original d'un justificatif de domicile daté de moins de 3 mois (quittance de loyer, facture d'un fournisseur d'énergie, d'eau, de téléphonie fixe, attestation de résidence) ;</li>
            <li>(Et une autorisation parentale pour les mineurs).</li>
        </ul>
        <p>12 Place Audiffard 10000 Troyes </p>
        <p>22 Rue de la République 10000 Troyes </p>
        <p>18 Route d’Auxerre 10120 Saint André les Vergers</p>
        <p><a href="http://www.lcl.fr">http://www.lcl.fr</a></p>

        <p><a href="http://www.auto-ecole-popeye.fr"><img src="img/sponsors/deals/popeye_resultat.png" style="float:right" class="img-thumbnail" /></a></p>
        <p> <h4 id="popeye"> Auto-école Popeye</h4> </p>
        <p>Tu veux passer ton permis de conduire ? Rejoins une des quatre agence de l’auto-école la mieux implantée sur Troyes. Tu pourras alors obtenir 50€ de réduction lors de ton inscription à l’auto-école Popeye sur présentation de ta carte étudiante (si tu cotises au BDE).</p>
        <p>C’est le permis moto qui t’intéresse ? Popeye te le fait passer pour 650 euros, tous frais compris ! </p>
        <p>Pour t’inscrire à l’auto-école, il te suffit de te munir de :</p>
        <ul type="circle">
            <li>8 timbres poste tarif préférentiel ;</li>
            <li>5 photos d’identité aux normes de la Préfecture ;</li>
            <li>la photocopie de carte d’identité recto/verso élève + responsable légal ;</li>
            <li>la photocopie de l’attestation de recensement ;</li>
            <li>la photocopie de l’ ASSR 2 ;</li>
            <li>2 justificatifs de domicile moins de 3 mois (+ attestation d'hébergement pour les mineurs) ;</li>
            <li>2 lettres max 50 g format 16X23 sans adresse.</li>
        </ul>
        <p>27 bis Avenue des Lombards (proche de l’UTT) 10000 Troyes</p>
        <p>69 Avenue Anatole France 10000 Troyes</p>
        <p>Place de la Libération (Centre Ville) 10000 Troyes</p>
        <p>4 Boulevard de l’Ouest 10600 La Chapelle Saint Luc</p>
        <p><a href="http://www.auto-ecole-popeye.fr">http://www.auto-ecole-popeye.fr</a></p>

        <p><a href="http://www.mgel.fr"><img src="img/sponsors/deals/mgel_resultat.png" style="float:right" class="img-thumbnail" /></a></p>
        <p> <h4 id="mgel">MGEL (Mutuelle Générale de Etudiants de l’Est) </h4> </p>
        <p>C’est l'assurance étudiante qui s'occupe d'assurer ton logement, de prendre en charge tes dépenses médicales à l'étranger (AssurWorld) et tes maladresses au quotidien (Assurance responsabilité civil obligatoire pour ton inscription à l'UTT).</p>
        <p>Pour toutes ces assurances, une seule adresse : <a href="https://bde.utt.fr/assurances-integration">https://bde.utt.fr/assurances</a></p>

        </div>
    </div>
@endsection
