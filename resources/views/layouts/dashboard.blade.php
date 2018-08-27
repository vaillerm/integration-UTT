@extends('layouts.master')

@section('sublayout-css')
    <link href="//maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.min.css" rel="stylesheet">
    <link href="//maxcdn.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.min.css" rel="stylesheet" type="text/css" />
    <link href="//code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css" rel="stylesheet" type="text/css" />
    <link href="{{ asset('/css/AdminLTE.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('/css/skins/skin-blue.min.css') }}" rel="stylesheet" type="text/css" />
@endsection

@section('bodycontent')
    <div class="skin-blue layout-top-nav">
        <div class="wrapper">
            <header class="main-header">
                <nav class="navbar navbar-static-top">
                    <div class="container-fluid">
                        <div class="navbar-header">
                            <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar-collapse" aria-expanded="false">
                                <span class="sr-only">Toggle navigation</span>
                                <i class="fa fa-bars"></i>
                            </button>
                            <a href="{{ route('dashboard.index') }}" class="navbar-brand"><b>Intégration</b> UTT</a>
                        </div>
                        <!-- Collect the nav links, forms, and other content for toggling -->
                        <div class="collapse navbar-collapse" id="navbar-collapse">
                            <ul class="nav navbar-nav">
                                    <li class="dropdown">
                                        <a class="dropdown-toggle" data-toggle="dropdown" role="button" href="">Défis <span class="caret"></span></a>
                                        <ul class="dropdown-menu">
                                            @if (Auth::user()->isOrga())
                                                <li><a href="{{ route('challenges.add') }}">Ajouter un défis</a></li>
                                                <li><a href={{ route("validation.list") }}>Liste des validations</a></li>
                                            @endif
                                            <li><a href={{ route('challenges.list') }}>Accéder à la liste des défis</a></li>
                                            @if(Auth::user()->team_id != null)
                                            <li><a href={{ route("challenges.sent") }}>Défis relevés </a></li>
                                            @endif
                                        </ul>
                                    </li>
                                @if (Auth::user()->ce)
                                    @if (!Auth::user()->team()->count())
                                        <li><a href="{{ route('dashboard.ce.teamlist') }}">Créer une équipe</a></li>
                                    @else
                                        <li><a href="{{ route('dashboard.ce.myteam') }}">Mon équipe</a></li>
                                        <li><a href="{{ route('dashboard.ce.teamlist') }}">Liste des équipes</a></li>
                                    @endif
                                @endif
                                @if (Auth::user()->isAdmin())
                                    <li class="dropdown">
                                        <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Parrainage <span class="caret"></span></a>
                                        <ul class="dropdown-menu">
                                            <li><a href="{{ route('dashboard.referrals.list') }}">Liste des parrains</a></li>
                                            <li><a href="{{ route('dashboard.referrals.validation') }}">Validation</a></li>
                                            <li><a href="{{ route('dashboard.referrals.signs.tc') }}">Fiches TC</a></li>
                                            <li><a href="{{ route('dashboard.referrals.signs.branch') }}">Fiches Branche</a></li>
                                            <li><a href="{{ route('dashboard.referrals.slides.tc') }}">Diapo TC</a></li>
                                            <li><a href="{{ route('dashboard.referrals.slides.branch') }}">Diapo Branche</a></li>
                                        </ul>
                                    </li>

                                    <li><a href="{{ route('dashboard.teams.list') }}">Équipes</a></li>
                                    <li class="dropdown">
                                        <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Utilisateurs <span class="caret"></span></a>
                                        <ul class="dropdown-menu">
                                            <li><a href="{{ route('dashboard.students.list') }}">Étudiants</a></li>
                                            <li><a href="{{ route('dashboard.students.add') }}">Ajouter un étu</a></li>
                                            <li><a href="{{ route('dashboard.students.list.preferences') }}">Bénévoles</a></li>
                                            <li><a href="{{ route('dashboard.newcomers.list') }}">Nouveaux</a></li>
                                            <li><a href="{{ route('dashboard.newcomers.list-progress') }}">Nouveaux avec progression</a></li>
                                        </ul>
                                    </li>
                                    <li><a href="{{ url('dashboard/event') }}">Évènements</a></li>
                                    <li><a href="{{ url('dashboard/checkin') }}">Checkins</a></li>
                                    <li><a href="{{ route('dashboard.emails.index') }}">Mails</a></li>
                                    <li class="dropdown">
                                        <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">WEI <span class="caret"></span></a>
                                        <ul class="dropdown-menu">
                                            <li><a href="{{ route('dashboard.wei') }}">Inscription</a></li>
                                            <li><a href="{{ route('dashboard.wei.graph') }}">Graphique</a></li>
                                            <li><a href="{{ route('dashboard.wei.search') }}">Gérer</a></li>
                                            <li><a href="{{ route('dashboard.wei.list') }}">Liste</a></li>
                                            <li><a href="{{ route('dashboard.wei.assign.team') }}">BUS: assignation d'équipe</a></li>
                                            <li><a href="{{ route('dashboard.wei.bus.list') }}">BUS: liste</a></li>
                                        </ul>
                                    </li>
                                    <li class="dropdown">
                                        <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Export <span class="caret"></span></a>
                                        <ul class="dropdown-menu">
                                            <li><a href="{{ route('dashboard.exports.newcomers') }}">Nouveaux -> parrains</a></li>
                                            <li><a href="{{ route('dashboard.exports.referrals') }}">Parrains -> nouveaux</a></li>
                                            <li><a href="{{ route('dashboard.exports.teams') }}">CE</a></li>
                                            <li><a href="{{ route('dashboard.exports.students') }}">Tous les utilisateurs</a></li>
                                        </ul>
                                    </li>
                                    <li class="dropdown">
                                        <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Configuration <span class="caret"></span></a>
                                        <ul class="dropdown-menu">
                                            <li><a href="{{ route('dashboard.configs.parameters') }}">Paramétrages</a></li>
                                        </ul>
                                    </li>
                                @elseif (Auth::user()->isModerator())
                                    <li class="dropdown">
                                        <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">WEI <span class="caret"></span></a>
                                        <ul class="dropdown-menu">
                                            <li><a href="{{ route('dashboard.wei') }}">Inscription</a></li>
                                            <li><a href="{{ route('dashboard.wei.search') }}">Gérer</a></li>
                                        </ul>
                                    </li>
                                @else
                                    <li><a href="{{ route('dashboard.wei') }}">WEI</a></li>
                                @endif
                            </ul>
                            <ul class="nav navbar-nav navbar-right">
                                <li class="dropdown">
                                    <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Mon compte <span class="caret"></span></a>
                                    <ul class="dropdown-menu">
                                        <li><a href="{{ route('dashboard.students.profil') }}"><i class="fa fa-user" aria-hidden="true"></i> Mon profil bénévole</a></li>
                                        <li role="separator" class="divider"></li>
                                        <li><a href="{{ route('menu') }}"><i class="fa fa-bars" aria-hidden="true"></i> Menu princpal</a></li>
                                        <li><a href="{{ route('index') }}"><i class="fa fa-home" aria-hidden="true"></i> Page d'accueil</a></li>
                                        <li><a href="{{ route('oauth.logout') }}"><i class="fa fa-power-off" aria-hidden="true"></i> Se déconnecter</a></li>
                                    </ul>
                                </li>
                            </ul>
                        </div><!-- /.navbar-collapse -->
                    </div><!-- /.container-fluid -->
                </nav>
            </header>

            <div class="content-wrapper">
                <div class="container">
                    <section class="content-header">
                        @include('display-errors')
                        @if (count($errors) > 0)
                            <div class="alert alert-danger">
                                <ul>
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif
                        <h1>
                            @yield('title')
                            <small>@yield('smalltitle')</small>
                        </h1>
                    </section>
                    <section class="content">
                        @yield('content')
                    </section>
                </div>
            </div>

            <footer class="main-footer">
                <div class="container">
                    <strong>En cas de problème,</strong> contacter <a href="mailto:integration@utt.fr">Intégration</a> (pas trop non plus hein) (non mais c'est censé marcher) (t'as rebooté ?).
                </div>
            </footer>
        </div>
    </div>
@endsection
