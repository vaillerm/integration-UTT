@extends('layouts.newcomer')

@section('title')
Ton équipe !
@endsection

@section('smalltitle')
@endsection

@section('content')
<div class="box box-default">
    @if (!Auth::user()->team)
        <div class="callout callout-danger">
            <h4>Tu n'es pas encore assigné à une équipe :/</h4>
            <p>
                Malheureusement, nous ne t'avons pas encore assigné à une équipe. Ça n'aurait pas vraiment dû arriver, mais bon.. manifestement c'est arrivé. <br/>
                Préviens nous du problème via la petite enveloppe en haut à droite de la page ou par email à <a href="mailto:integration@utt.fr">integration@utt.fr</a>
            </p>
        </div>
    @else

        <div class="box-header with-border">
            <h3 class="box-title"><strong>{{ Auth::user()->team->name }}</strong>, ton équipe</h3>
        </div>
            <div class="box-body">
                <div class="thumbnail col-lg-2">
                    <img src="{{ asset('/uploads/teams-logo/'.Auth::user()->team->id.'.'.Auth::user()->team->img) }}" alt="Photo"/>
                </div>
                    <div class="caption col-lg-10">
                        @if(substr(Auth::user()->team->facebook, 0, 4) == 'http')
                            <div style="position:relative;margin-bottom:5px;">
                                <i class="fa fa-facebook" aria-hidden="true" style="position:absolute;top:3px;vertical-align:bottom;"></i>
                                <span style="margin-left:25px;text-align:justify;"><a href="{{ Auth::user()->team->facebook }}">Groupe Facebook</a></span>
                            </div>
                        @endif
                        <div style="position:relative;margin-bottom:5px;">
                            <i class="fa fa-comment" aria-hidden="true" style="position:absolute;top:3px;vertical-align:bottom;"></i>
                            <p style="margin-left:25px;margin-right:5px;text-align:justify;">{!! nl2br(e(Auth::user()->team->description)) !!}</p></div>
                    </div>
                    <hr style="margin-top:0px;"/>
                    <div class="clearfix"></div>
                    <h4>Mais pourquoi avoir une équipe ?</h4>
                    <p>
                        Ton équipe est composé de nouveaux et de {{ Auth::user()->team->ce->count() }} chefs d'équipes.
                        Ils t'accompagneront pendant toute ta semaine d'intégration, au cours des différents jeux et activités te seront proposés.<br/>
                        Tout au long de la semaine, tu pourras rapporter des points à ton équipe en gagnant les différents jeux et en représentant au mieux ton équipe.<br/>
                    </p>
                    <p>
                        Pour être sûr d'avoir un maximum de chances de marquer des points, commence par faire les actions suivantes :
                    <ul>
                        <li>Rejoins le <a href="{{ Auth::user()->team->facebook }}">groupe Facebook</a> de ton équipe</li>
                        <li>Fais un déguisement qui déchire !
                            <br/>Si tu ne sais pas quoi faire, demande des conseils à ton équipe sur le groupe Facebook.</li>
                            <li>Poste une photo de ton déguisement dans le groupe Facebook de ton équipe.</li>
                        <li>Inscris-toi au week-end pour terminer ton intégration en beauté !</li>
                    <p>

                    </p>
                    <hr/>

                    <div class="text-center">
                    @if(Auth::user()->isChecked('team_disguise'))
                        <h4 id="question">Félicitations, tu as rejoins le groupe Facebook de ton équipe et tu as fait ton déguisement !</h4>
                        <a href="{{ route('newcomer.team', [ 'step' => 'cancel']) }}#question" class="btn btn-danger">Ce n'est pas le cas ?</a>

                        <div class="text-center">
                            <a class="btn btn-primary" href="{{{ route('newcomer.'.Auth::user()->getNextCheck()['page']) }}}">Prochaine action à faire<br/><strong>{{{ Auth::user()->getNextCheck()['action'] }}}</strong></a>
                        </div>
                    @else
                        <h4 id="question">As-tu fait ton déguisement et rejoint ton équipe sur Facebook ?</h4>
                        <a href="{{ route('newcomer.team', [ 'step' => 'yes']) }}#question" class="btn btn-success">Oui !</a>
                    @endif
                    </div>
            </div>
        @endif
</div>
@endsection
