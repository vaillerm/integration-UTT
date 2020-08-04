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
                et nouveaux UTTiens autour des différents repas et activités que nous organisons pour toi.
            </p>
            <p>
                Ta première semaine dans notre belle école sera plutôt chargée en activités,
                mais ne t'inquiètes pas tu receveras un planning détaillé de ta semaine dès ton arrivée.
                @if(Auth::user()->branch == 'TC')
                    Tout ce que tu as à savoir pour le moment, c'est que tu dois être là <strong>{{ Config::get('services.reentry.tc.date') }} à {{ Config::get('services.reentry.tc.time') }} à l'UTT</strong>,
                    pour que nous puissions <strong>t'offrir un petit dej'</strong> de bienvenue.
                @elseif( in_array( Auth::user()->branch, array('ISC','PAIP', 'RE') ) )
                    Tout ce que tu as à savoir pour le moment, c'est que tu dois être là <strong>{{ Config::get('services.reentry.masters.date') }} à {{ Config::get('services.reentry.masters.time') }} à l'UTT</strong>,
                    pour que nous puissions <strong>t'offrir un petit dej'</strong> de bienvenue.
                @elseif(Auth::user()->branch != 'MM')
                    Tout ce que tu as à savoir pour le moment, c'est que tu dois être là <strong>{{ Config::get('services.reentry.branches.date') }} à {{ Config::get('services.reentry.branches.time') }} à l'UTT</strong>,
                    pour que nous puissions <strong>t'offrir un petit dej'</strong> de bienvenue.
                @endif
            </p><p>
                Pour te tenir au courant des dernières infos sur ton intégration, tu <del>peux</del> <strong>dois</strong> suivre la page Facebook, l'insta et/ou le snapchat :)
            </p>
                <div class="text-center">
                    <!-- <iframe src="https://www.facebook.com/plugins/page.php?href=https%3A%2F%2Fwww.facebook.com%2Fbde.utt&tabs&width=340&height=70&small_header=true&adapt_container_width=false&hide_cover=false&show_facepile=false&appId=248954165119490" width="340" height="70" style="border:none;overflow:hidden" scrolling="no" frameborder="0" allowTransparency="true"></iframe> TODO : Fix issue where iframe is not loaded (maybe because of headers ?) -->
                    <a href="https://www.facebook.com/bde.utt/" /><img src="{{ asset('img/icons/facebook.png') }}" style="height: 70px; vertical-align:top;"/></a>
                    <a href="https://www.instagram.com/bdeutt/" /><img src="{{ asset('img/icons/instagram.png') }}" style="height: 70px; vertical-align:top;"/></a>
                    <a href="https://www.snapchat.com/add/integrationutt" /><img src="{{ asset('img/icons/snapchat.svg') }}" style="height: 70px; vertical-align:top;"/></a>
                </div>
            <p>
                Ce site est là pour te permettre d'être prêt pour ton intégration,
                il va récapituler tous les petits trucs auxquels tu devras penser.
            </p>
            <div class="text-center">
                <a class="btn btn-primary" href="{{{ route('newcomer.'.Auth::user()->getNextCheck()['page']) }}}">Prochaine action à faire<br/><strong>{{{ Auth::user()->getNextCheck()['action'] }}}</strong></a>
            </div>
            <p>
                Si tu as la moindre question, tu peux nous contacter sur <a href="https://www.facebook.com/bde.utt/" target="_blank">Facebook</a> ou en <a href="{{ route('contact') }}">cliquant ici</a> !
            </p>
        </div>

    </div>
@endsection
