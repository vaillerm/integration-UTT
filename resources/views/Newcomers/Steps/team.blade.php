@extends('layouts.newcomer')

@section('title')
Ton équipe !
@endsection

@section('smalltitle')
@endsection

@section('content')
<div class="box box-default">
    @if (!Auth::user()->team || !Auth::user()->team->name)
        <div class="callout">
            <h4>Tu n'es pas encore assigné à une équipe</h4>
            <p>
                L'assignation aux équipes n'a pas encore commencé, tu recevras un mail prochainement lorsque l'une d'elle te sera attribué ;)
            </p>
        </div>
    @else

        <div class="box-header with-border">
            <h3 class="box-title"><strong>{{ Auth::user()->team->name }}</strong>, ton équipe</h3>
        </div>
            <div class="box-body">
                <p>
                    Pendant ton intégration, {{ $factions->count() }} factions vont s'affronter sur le thème <strong>{{ Config::get('services.theme')}}</strong> :
                    @foreach ($factions as $i => $faction)
                        <strong>{{ $faction->name }}</strong>
                        @if ($i == $faction->count()-2)
                            et
                        @elseif ($i != 0 && $i != $faction->count()-1)
                            ,
                        @endif
                    @endforeach
                    .
                </p>
                <div class="thumbnail col-lg-2">
                    <img src="{{ asset('/uploads/teams-logo/'.Auth::user()->team->id.'.'.Auth::user()->team->img) }}" alt="Photo"/>
                </div>
                <div class="caption col-lg-10">
                    <div class="clearfix"></div>


                    <div style="position:relative;margin-bottom:5px;">
                        <i class="fa fa-users" aria-hidden="true" style="position:absolute;top:3px;vertical-align:bottom;"></i>
                        <span style="margin-left:25px;text-align:justify;">Ton équipe s'appelle <strong>{{ Auth::user()->team->name }}</strong>
                            {!!
                                Auth::user()->team->safe_name && Auth::user()->team->name != Auth::user()->team->safe_name
                                ? '(<em>'.e(Auth::user()->team->safe_name).'</em>)'
                                : ''
                            !!}
                        </span>
                    </div>
                    <div style="position:relative;margin-bottom:5px;">
                        <i class="fa fa-shield" aria-hidden="true" style="position:absolute;top:3px;vertical-align:bottom;"></i>
                        <span style="margin-left:25px;text-align:justify;">
                            Elle est dans la faction <strong>{{ Auth::user()->team->faction->name }}</strong>
                        </span>
                    </div>
                    @if(substr(Auth::user()->team->facebook, 0, 4) == 'http')
                        <div style="position:relative;margin-bottom:5px;">
                            <i class="fa fa-facebook" aria-hidden="true" style="position:absolute;top:3px;vertical-align:bottom;"></i>
                            <span style="margin-left:25px;text-align:justify;">Rejoins ton équipe sur le <a href="{{ Auth::user()->team->facebook }}" target="_blank">groupe Facebook</a></span>
                        </div>
                    @endif
                </div>
                <div class="clearfix"></div>
                <h4>Ils ont un message pour toi !</h4>
                <p style="text-align:justify;font-size:1.1em"><em>{!! nl2br(e(Auth::user()->team->description)) !!}</em></p>
                <hr/>
                <h4>Mais pourquoi avoir une équipe ?</h4>
                <p>
                    Ton équipe est composée de nouveaux et de {{ Auth::user()->team->ce->count() }} chefs d'équipes.
                    Ils t'accompagneront pendant toute ta semaine d'intégration, au cours des différents jeux et activités te seront proposés.<br/>
                    Tout au long de la semaine, tu pourras rapporter des points à ton équipe et à ta faction en gagnant les différents jeux et en représentant au mieux ton équipe.<br/>
                </p>
                <p>
                    Pour être sûr d'avoir un maximum de chances de marquer des points, commence par faire les actions suivantes :
                <ul>
                    <li>Rejoins le <a href="{{ Auth::user()->team->facebook }}" target="_blank">groupe Facebook</a> de ton équipe</li>
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
                    <h4 id="question">As-tu fait ton déguisement et rejoins ton équipe sur Facebook ?</h4>
                    <a href="{{ route('newcomer.team', [ 'step' => 'yes']) }}#question" class="btn btn-success">Oui !</a>
                @endif
                </div>
            </div>
        @endif
</div>
@endsection
