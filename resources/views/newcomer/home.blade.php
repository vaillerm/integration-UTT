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
                digne de ce nom ! Le but de cette semaine est de rencontrer les anciens
                et nouveaux UTTiens autour des différents repas et activitées que nous organisons pour toi.
            </p>
            <p>
                Ta première semaine dans notre belle école sera plutôt chargée en activité,
                mais ne t'inquiète pas tu receveras un planning détaillé de ta semaine dès ton arrivée.
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
                Ce ce site est là pour te permettre d'être prêt pour ton intégration,
                il va récapituler tout les petits trucs auxquels tu devras penser.
            </p>
            <div class="text-center">
                <a class="btn btn-primary" href="{{{ route('newcomer.'.Auth::user()->getNextCheck()['page']) }}}">Prochaine action à faire<br/><strong>{{{ Auth::user()->getNextCheck()['action'] }}}</strong></a>
            </div>
            <p>
                Si tu as la moindre question, tu peux nous contacter sur <a href="https://www.facebook.com/integration.utt">Facebook</a> ou par email <a href="mailto:integration@utt.fr">integration@utt.fr</a> !
            </p>
            <br/><br/>
            <p>
                Si tu n'as pas reçu ton courrier, ou si tu souhaites récupérer les versions numériques de ce que tu as reçu par courrier, tu peux les télécharger en cliquant sur les liens ci-dessous :
            </p>
            <div class="text-center">
                <a class="btn btn-default" href="{{{ @asset('docs/nutt.pdf') }}}">Ton N'UTT d'intégration</a>
                <a class="btn btn-default" href="{{{ @asset('docs/cahiervacances.pdf') }}}">Ton cahier de vacances</a>
                <a class="btn btn-default" href="{{{ route('newcomer.myletter') }}}">Ta fiche nouveau</a>
            </div>
        </div>

    </div>
@endsection
