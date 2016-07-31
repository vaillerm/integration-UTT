@extends('layouts.newcomer')

@section('title')
Accueil
@endsection

@section('smalltitle')
Informations diverses pour toi !
@endsection

@section('content')
<div class="box box-default">

        <div class="box-header with-border">
            <h3 class="box-title">Bonjour à toi nouveau !</h3>
        </div>
        <div class="box-body">
            <p>
                Tu fais maintenant partie de la grande famille des UTTiens !
                Et pour t'accueillir, nous te préparons une semaine d'intégration
                digne de ce nom ! Le but de cette semaine et de rencontrer les anciens
                et nouveaux UTTiens autour des différents repas et activitées que nous organisons pour toi.
            </p>
            <p>
                Ta première semaine dans notre belle école sera plutôt chargé en activité,
                mais ne t'inquiète pas tu recevera un planning détaillé te ta semaine dès ton arrivée.
                @if(Auth::user()->branch == 'TC')
                    Tout ce que tu as à savoir pour le moment, c'est que tu dois être là <strong>lundi 5 septembre à 8h à l'UTT</strong>,
                    pour que nous puissions <strong>t'offrir un petit dej'</strong> de bienvenue.
                @elseif(Auth::user()->branch != 'MM')
                    Tout ce que tu as à savoir pour le moment, c'est que tu dois être là <strong>mardi 6 septembre à 8h à l'UTT</strong>,
                    pour que nous puissions <strong>t'offrir un petit dej'</strong> de bienvenue.
                @endif
            </p><p>
                Pour te tenir au courant des dernières infos sur ton Intégration, tu peux suivre la page facebook :)
            </p>
                <div class="text-center">
                    <iframe src="https://www.facebook.com/plugins/page.php?href=https%3A%2F%2Fwww.facebook.com%2Fintegration.utt&tabs&width=340&height=70&small_header=true&adapt_container_width=false&hide_cover=false&show_facepile=false&appId=248954165119490" width="340" height="70" style="border:none;overflow:hidden" scrolling="no" frameborder="0" allowTransparency="true"></iframe>
                </div>
            <p>
                Le but de ce site est de te permettre d'être prêt pour ton intégration,
                il va récapituler tout les petits truc auxquels tu devras penser.
            </p>
            <div class="text-center">
                <a class="btn btn-primary" href="{{{ route('newcomer.'.Auth::user()->getNextCheck()['page']) }}}">Prochaine action à faire<br/><strong>{{{ Auth::user()->getNextCheck()['action'] }}}</strong></a>
            </div>
            <p>
                Tu as de la chance, cette année, ce site a été fortement retravaillé
                pour ajouter plein de fonctionnalités sympa. Cependant, elles sont pas encore toutes prêtes,
                donc il va être en constante evolution pendant le mois d'aout. Cela veut aussi
                dire que tu pourra sans doute trouver quelques bugs. Dans ce cas, n'hésite pas,
                envoi un email à <a href="mailto:integration@utt.fr">integration@utt.fr</a> !
            </p>

        </div>

    </div>
@endsection
