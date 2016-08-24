@extends('layouts.newcomer')

@section('title')
Bon plans
@endsection

@section('smalltitle')
Que l'Intégration a trouvé pour toi !
@endsection

@section('content')
    <div class="box box-default">
        <div class="box-header with-border">
            <h3 class="box-title">Les bon plans de l'intégration !</h3>
        </div>
        <div class="box-body">
        <p><h4> Les Partenaires de ton intégration : </h3></p>

        <ul>
            <li><a href="#damonte">Damonte</a></li>
            <li><a href="#ada">ADA</a></li>
            <li><a href="#lcl">LCL</a></li>
            <li><a href="#beijaflore">Beijaflore</a></li>
            <li><a href="#popeye">Auto-école Popeye</a></li>
            <li><a href="#mgel">MGEL (Mutuelle Générale de Etudiants de l’Est)</a></li>
            <li><a href="#dell">Dell : Opération de rentrée</a></li>
            <li><a href="#menphis">Memphis Coffee</a></li>
            <li><a href="#carrefour">Carrefour</a></li>
            <li><a href="#emergence">Emergence</a></li>
            <li><a href="#bureautique">Acces Bureautique</a></li>
        </ul>

        <p><h4> Les Partenaires de ta soirée d'intégration : </h3></p>

        <ul>
            <li><a href="#concerts">Festival des nuits de Champagne</a></li>
            <li><a href="#lasergame">Lasergame Troyes</a></li>
            <li><a href="#accroland">Accroland Troyes</a></li>
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
        <p>C’est LE spécialiste en location de véhicules pour les étudiants. Tu pourras bénéficier de 20% de réduction sur ta location.
        Tu viens d’avoir ton permis ?
        Pas de soucis, avec ADA, dès 18 ans, tu peux louer tous les véhicules sans frais supplémentaire !</p>
        <p> 32 Rue de la Fontaine Saint Martin 10120 Saint André les Vergers </p>
        <p><a href="http://www.ada.fr/location-voiture-troyes.html">http://www.ada.fr/location-voiture-troyes.html</a></p>

        <p><a href="http://www.lcl.fr"><img src="img/sponsors/deals/lcl_resultat.jpg" style="float:right" class="img-thumbnail" /></a></p>
        <p> <h4 id="lcl"> LCL </h4> </p>
        <p>Le Crédit Lyonnais t’offrira 55€ à l’ouverture d’un compte chez eux (ils t’offrent le WE d’inté). Tu pourras aussi bénéficier d’une carte bancaire pour seulement 1€ par mois. En cas d’imprévu, un découvert de 400€ te sera autorisé. Enfin, tu pourras bénéficier d’un emprunt au taux exceptionnel de  1 %</p>
        <p>Tu pourras même ouvrir ton compte à l’UTT le mardi après-midi ! </p>
        <p>Il te suffit de te munir :</p>
        <ul type="circle">
            <li>d’un justificatif d’identité (carte d’identité, passeport, carte de séjour)</li>
            <li>d’un original d'un justificatif de domicile daté de moins de 3 mois (quittance de loyer, facture d'un fournisseur d'énergie, d'eau, de téléphonie fixe, d'internet fixe, attestation de résidence)</li>
            <li>(Et une autorisation parentale pour les mineurs)</li>
        </ul>
        <p>12 Place Audiffard 10000 Troyes </p>
        <p>22 Rue de la République 10000 Troyes </p>
        <p>18 Route d’Auxerre 10120 Saint André les Vergers</p>
        <p><a href="http://www.lcl.fr">http://www.lcl.fr</a></p>

        <p><a href="http://www.beijaflore.com"><img src="img/sponsors/deals/beijaflore_resultat.jpg" style="float:right" class="img-thumbnail" /></a></p>
        <p> <h4 id="beijaflore"> Beijaflore </h4> </p>
        <p>Beijaflore est un cabinet de conseil en management informatique qui oriente et pilote les projets de transformation des grandes entreprises dans le but d’accroître leur performance et leur compétitivité.</p>
        <p>Tu recherches un stage en informatique ? N’hésite pas, contacte les au plus vite.</p>
        <p><a href="http://www.beijaflore.com">http://www.beijaflore.com</a></p>


        <p><a href="http://www.auto-ecole-popeye.fr"><img src="img/sponsors/deals/popeye_resultat.png" style="float:right" class="img-thumbnail" /></a></p>
        <p> <h4 id="popeye"> Auto-école Popeye</h4> </p>
        <p>Tu veux passer ton permis de conduire ? Rejoins une des quatre agence de l’auto-école la mieux implantée sur Troyes. Tu pourras alors obtenir 50€ de réduction lors de ton inscription à l’auto école Popeye sur présentation d’une attestation de cotisation (à demander au rideau du BDE).</p>
        <p>C’est le permis Moto qui t’intéresse ? Popeye te le fait passer pour 650 euros, tous frais compris ! </p>
        <p>Pour t’inscrire à l’auto-école, il te suffit de te munir de :</p>
        <ul type="circle">
            <li>8 timbres poste tarif préférentiel</li>
            <li>5 photos d’identité aux normes de la Préfecture</li>
            <li>la photocopie de carte d’identité recto/verso élève + responsable légal</li>
            <li>la photocopie de l’attestation de recensement</li>
            <li>la photocopie de l’ ASSR 2</li>
            <li>2 justificatifs de domicile moins de 3 mois (+ attestation d'hébergement pour les mineurs)</li>
            <li>2 lettres max 50 g format 16X23 sans adresse</li>
        </ul>
        <p>27bis Avenue des Lombards (Proche de l’UTT) 10000 Troyes</p>
        <p>69 Avenue Anatole France 10000 Troyes</p>
        <p>Place de la Libération (Centre Ville) 10000 Troyes</p>
        <p>4 Boulevard de l’Ouest 10600 La Chapelle Saint Luc</p>
        <p><a href="http://www.auto-ecole-popeye.fr">http://www.auto-ecole-popeye.fr</a></p>

        <p><a href="http://www.mgel.fr"><img src="img/sponsors/deals/mgel_resultat.png" style="float:right" class="img-thumbnail" /></a></p>
        <p> <h4 id="mgel">MGEL (Mutuelle Générale de Etudiants de l’Est) </h4> </p>
        <p>C’est la sécurité sociale étudiante qui te permettra de te faire rembourser tes frais médicaux. Elle t’offre une couverture nationale, un remboursement immédiat, un échange avec la mutuelle de tes parents, ainsi que le réseau national Emevia.</p>
        <p>76 Rue du Général de Gaulle 10000 Troyes</p>
        <p><a href="http://www.mgel.fr">http://www.mgel.fr</a></p>

        <p><a href="http://www.portable-etudiant.com/?Id=T305UEAUTMITZ4ICZJNN"><img src="img/sponsors/deals/dell_resultat.jpg" style="float:right" class="img-thumbnail" /></a></p>
        <p> <h4 id="dell"> Dell : Opération de rentrée</h4> </p>
        <p> Tu crains que ton vieil ordi ne puisse pas tenir la cadence ?
            Notre partenaire Dell te propose en passant par le biais de l’intégration des réductions exceptionnelles ! </p>
        <p> Pour en bénéficier clique sur ce super lien : <a href="http://www.portable-etudiant.com/?Id=T305UEAUTMITZ4ICZJNN">“SUPER LIEN”</a></p>
        <p> L'identifiant (login) est : <strong> INTEGRATION2016 </strong> </p>

        <p><a href="http://www.memphis-coffee.com/memphis-coffee-troyes"><img src="img/sponsors/deals/menphis_resultat.png" style="float:right" class="img-thumbnail" /></a></p>
        <p> <h4 id="menphis"> Memphis Coffee </h4> </p>
        <p>C’est le resto de burgers de l’agglomération troyenne. Du lundi au jeudi, tu pourras obtenir 25% de réduction sur l’addition en présentant ta carte étudiante.</p>
        <p>En plus, le patron est un ancien Uttien !</p>
        <p>Rue de l’Avenir 10410 Saint Parres aux Tertres</p>
        <p><a href="http://www.memphis-coffee.com/memphis-coffee-troyes">http://www.memphis-coffee.com/memphis-coffee-troyes</a></p>

        <p><a href="http://www.carrefour.fr"><img src="img/sponsors/deals/carrouf_resultat.jpeg" style="float:right" class="img-thumbnail" /></a></p>
        <p> <h4 id="carrefour"> Carrefour </h4> </p>
        <p>C’est LE supermarché qui te permettra de faire tes courses au meilleur prix dans l’agglomération troyenne ! Tu y trouveras tout le nécessaire: alimentation, produits ménagers, électroménager, vaisselle, ameublement, librairie…</p>
        <p>Et le mardi, pour 30 euros d’achats, bénéficie d’un bon de 3 euros pour la semaine d’après sur présentation de ta carte étudiante à l’accueil du magasin !</p>
        <p>17 Avenue Charles de Refuge 10120 Saint André les Vergers</p>
        <a href="http://www.carrefour.fr">http://www.carrefour.fr</a>


        <p> <h4 id="emergence"> Emergence </h4></p>
        <p>L’espace Argence est une salle de concert à Troyes. Tu pourras obtenir des tarifs préférentiels pour les concerts grâce au partenariat de ton BDE avec Emergence.</p>
        <p>Ils t’organisent en particulier une magnifique soirée le 24 Septembre avec Broken Back !</p>
        <p>47 Rue Maurice Romagon 10000 Troyes</p>
        <p><a href="http://www.emergence-production.com">http://www.emergence-production.com</a></p>


        <p> <h4 id="bureautique"> Acces Bureautique </h4></p>
        <p>Elle fournit les magnifiques imprimantes qui ont permis d’imprimer tes courriers de rentrée ! Alors remercie les chaleureusement !</p>

        <p><a href="http://www.nuitsdechampagne.com/"><img src="img/sponsors/deals/concerts_resultat.jpg" style="float:right" class="img-thumbnail" /></a></p>
        <p> <h4 id="concerts"> Nuits de Champagne </h4></p>
        <p>Le Festival des Nuits de Champagne est cette année partenaire de la Soirée d’intégration et donne des lots pour la tombola. Ils distribuent 16 places pour les concerts des nuits de Champagne qui se dérouleront du 23 au 29 octobre 206 pour la 29eme édition du festival !</p>
        <p><a href="http://www.nuitsdechampagne.com/">http://www.nuitsdechampagne.com/</a></p>

        <p><a href="http://www.lasergame-evolution.com/fr/14/Troyes/"><img src="img/sponsors/deals/lasergame_resultat.png" style="float:right" class="img-thumbnail" /></a></p>
        <p> <h4 id="lasergame"> Lasergame Troyes </h4></p>
        <p>Le Lasergame Troyes, c’est l’endroit où aller pour te défouler après des examens compliqués ou après une journée de cours ! </p>
        <p>15 places sont en jeu lors de la tombola de la soirée d’intégration ! Prends vite ta place !</p>
        <p>11 Boulevard Georges Pompidou 10000 Troyes</p>
        <p><a href="http://www.lasergame-evolution.com/fr/14/Troyes/">http://www.lasergame-evolution.com/fr/14/Troyes/</a></p>

        <p><a href="http://www.accroland.com"><img src="img/sponsors/deals/accroland_resultat.png" style="float:right" class="img-thumbnail" /></a></p>
        <p> <h4 id="accroland"> Accroland Troyes </h4></p>
        <p>Le Parc Accroland t’accueille pour faire le plein de sensations fortes ! 9 parcours t’attendent à proximité immédiate de Troyes ! </p>
        <p>5 Places sont en jeu lors de la tombola de la soirée d’inté ! Saisis vite ta chance.</p>
        <p>4 rue du lavoir 10800 Rouilly Saint loup</p>
        <p><a href="http://www.accroland.com">http://www.accroland.com/</a></p>
        </div>
    </div>
@endsection
