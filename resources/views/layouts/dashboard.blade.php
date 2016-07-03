@extends('layouts.master')

@section('css')
<link href="//maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.min.css" rel="stylesheet">
<link href="//maxcdn.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.min.css" rel="stylesheet" type="text/css" />
<link href="//code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css" rel="stylesheet" type="text/css" />
<link href="{{ @asset('/css/AdminLTE.min.css') }}" rel="stylesheet" type="text/css" />
<link href="{{ @asset('/css/skins/skin-blue.min.css') }}" rel="stylesheet" type="text/css" />
@endsection

@section('bodycontent')
    <div class="skin-blue layout-top-nav">
        <div class="wrapper">
            <header class="main-header">
                <nav class="navbar navbar-static-top">
                    <div class="container">
                        <div class="navbar-header">
                            <a href="{{ route('dashboard.index') }}" class="navbar-brand"><b>Intégration</b> UTT</a>
                            <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar-collapse">
                                <i class="fa fa-bars"></i>
                            </button>
                        </div>

                        <div class="collapse navbar-collapse pull-left" id="navbar-collapse">
                            <ul class="nav navbar-nav">
                                <li><a href="{{ route('dashboard.referrals') }}">Parrains</a></li>
                                <li><a href="{{ route('dashboard.validation') }}">Validation</a></li>
                                <li><a href="{{ route('dashboard.newcomers') }}">Nouveaux</a></li>
                                <li><a href="{{ route('dashboard.students.list') }}">Etudiants</a></li>
                                <li><a href="{{ route('dashboard.teams') }}">Équipes</a></li>
                                <li><a href="{{ route('dashboard.exports') }}">Export</a></li>
                                <li><a href="{{ route('dashboard.championship') }}">Factions</a></li>
                                <li><a href="{{ route('dashboard.wei') }}">WEI</a></li>
                            </ul>
                        </div>

                        <div class="navbar-custom-menu">
                            <ul class="nav navbar-nav">
                                <li class="dropdown user user-menu">
                                    <li><a href="{{ route('oauth.logout') }}"><i class="fa fa-power-off"></i> Se déconnecter</a></li>
                                </li>
                            </ul>
                        </div>
                    </div>
                </nav>
            </header>

            <div class="content-wrapper">
                <div class="container">
                    <section class="content-header">
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
                    <div class="pull-right hidden-xs">
                        <b>Version</b> {{ Config::get('services.version.hash')}}


                    </div>
                    <strong>En cas de problème,</strong> contacter <a href="mailto:aurelien.labate@utt.fr">Alabate</a> (pas trop non plus hein) (non mais c'est censé marcher) (t'as rebooté ?).
                </div>
            </footer>
        </div>
    </div>
@endsection
