@extends('layouts.dashboard')

@section('css')
    <link rel="stylesheet" href="{{ asset('css/flipclock.css') }}">
@endsection

@section('js')
    <script src="{{ asset('js/flipclock.min.js') }}"></script>
    <script>
        var countdown = $('.countdown').FlipClock({{ (new DateTime(Config::get('services.wei.registrationStart')))->getTimestamp() - (new DateTime())->getTimestamp() }}, {
            countdown: true,
            clockFace: 'DailyCounter',
            language: 'french',
        });
    </script>
@endsection

@section('title')
    Inscription au WEI
@endsection

@section('smalltitle')
    Le Week-End d'Intégration
@endsection

@section('content')
    @if(Authorization::can('volunteer','wei'))

        <div class="box box-default">
            <div class="box-header with-border">
                <h3 class="box-title">Mon inscription au Week-End</h3>
            </div>
            <div class="box-body">
                <p>Cette page permet de t'inscrire au WEI en tant que bénévole. Pour que ton inscription soit valide, il faut que tu aies payé le prix de ton WEI et donné la caution. Cependant, cela ne t'assure pas de pouvoir venir au WEI. Tu ne saura ça que le jeudi de la semaine d'inté par mail ou sur cette page.</p>

                <h4>Pourquoi valider qu'au dernier moment ?</h4>
                <p>
                    Ca ne fait malheureusement plaisir à personne. Le problème, c'est que le nombre de places disponibles pour les bénévoles dépend du nombre de nouveaux présents. Or les nouveaux s'inscrivent pendant la semaine d'inté.
                </p>
                <h4>Est-ce qu'on peut payer autrement que par internet ?</h4>
                <p>
                    Non :/<br/>Ça nous demande bien plus de travail et de temps de recevoir des paiements autrement qu'en ligne.
                    Nous le faisons pour les nouveaux, car les parents ont parfois peur de payer en ligne.
                    Mais pour les bénévoles, ce n'est pas le cas. Si vous ne pouvez pas, arrangez vous avec vos potes pour qu'ils payent à votre place et vous les rembourserez en McDo. ;-)
                </p>

                @if(!Auth::user()->wei)
                    @if(Config::get('services.wei.open') === '1')
                        @if((new DateTime(Config::get('services.wei.registrationStart'))) > (new DateTime()))
                            <div class="box box-default">
                                <div class="box-header with-border">
                                    <h3 class="box-title">Ouverture des inscriptions pour le week-end dans...</h3>
                                </div>
                                <div class="box-body text-center">
                                    <div class="countdown hidden-xs" style="width:640px;margin:20px auto;"></div>
                                    <big class="visible-xs">{{ ((new DateTime(Config::get('services.wei.registrationStart')))->diff(new DateTime()))->format('%d jours %h heures %i minutes et %s secondes') }}</big>
                                </div>
                            </div>
                        @else
                            <a href="{{route('dashboard.wei.health')}}" class="btn btn-primary">S'inscrire au Week-End</a><br/>
                            <p>Si tu as le moindre souci, n'hésite pas à nous contacter à <a href="mailto:integration@utt.fr">integration@utt.fr</a> en précisant en tant que quoi tu viens (bénévole, ce, orga...)</p>
                        @endif
                    @else
                        <strong>L'inscription au WEI n'est pas encore ouverte !</strong>
                    @endif

                @else

                    @if($health)
                        <big><a href="{{ route('dashboard.wei.health') }}">
                            <i class="fa fa-check-square-o" aria-hidden="true"></i>
                            Modifier son profil santé
                        </a></big><br/>
                    @else
                        <big><a href="{{ route('dashboard.wei.health') }}">
                            <i class="fa fa-square-o" aria-hidden="true"></i>
                            Remplir son profil santé
                        </a></big><br/>
                    @endif

                    @if($wei)
                        <big>
                            <i class="fa fa-check-square-o" aria-hidden="true"></i>
                            Payer le week-end
                        </big><br/>
                    @else
                        <big><a href="{{ route('dashboard.wei.pay') }}">
                                <i class="fa fa-square-o" aria-hidden="true"></i>
                                Payer le week-end
                            </a></big><br/>
                    @endif

                    @if($sandwich)
                        <big>
                            <i class="fa fa-check-square-o" aria-hidden="true"></i>
                            Prendre le panier repas du vendredi midi
                        </big><br/>
                    @else
                        <big><a href="{{ route('dashboard.wei.pay') }}">
                                <i class="fa fa-square-o" aria-hidden="true"></i>
                                Prendre le panier repas du vendredi midi
                            </a></big><br/>
                    @endif

                    @if($guarantee)
                        <big>
                            <i class="fa fa-check-square-o" aria-hidden="true"></i>
                            Déposer la caution
                        </big><br/>
                    @else
                        <big><a href="{{ route('dashboard.wei.guarantee') }}">
                                <i class="fa fa-square-o" aria-hidden="true"></i>
                                Déposer la caution
                            </a></big><br/>
                    @endif
                @endif
            </div>
        </div>

        @if(Auth::user()->wei)
            @if($validated == 1)
                <div class="callout callout-success">
                    Ton inscription est validée, tu peux venir au WEI !
                </div>
            @elseif($validated == -1)
                <div class="callout callout-danger">
                    Désolé, ton inscription est refusée, tu ne peux pas venir au WEI. :/
                </div>
            @else
                <div class="callout callout-info">
                    Ton inscription est en attente de validation d'un admin !
                </div>
            @endif
        @endif

    @else
        <div class="box box-default">
            <div class="box-header with-border">
                <h3 class="box-title">Oups... Il semblerait que tu ne puisses pas t'inscire au WEI. Si tu penses qu'il s'agit d'une erreur, contacte nous.</h3>
                <p><a href="mailto:integration@utt.fr">integration@utt.fr</a></p>
            </div>
        </div>
    @endif


@endsection
