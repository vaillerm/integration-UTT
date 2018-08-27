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
                    <div class="container">
                        <div class="navbar-header">
                            <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar-collapse" aria-expanded="false">
                                <span class="sr-only">Toggle navigation</span>
                                <i class="fa fa-bars"></i>
                            </button>
                            <a href="{{ route('newcomer.home') }}" class="navbar-brand"><b>Intégration</b> UTT</a>
                        </div>
                        <!-- Collect the nav links, forms, and other content for toggling -->
                        <div class="collapse navbar-collapse" id="navbar-collapse">
                            <ul class="nav navbar-nav">
                                <li><a href="{{ route('newcomer.profil') }}">Profil{!! Auth::user()->isPageChecked('profil')?' <i class="fa fa-check" aria-hidden="true"></i>':'' !!}</a></li>
                                <li><a href="{{ route('newcomer.referral') }}">Parrain{!! Auth::user()->isPageChecked('referral')?' <i class="fa fa-check" aria-hidden="true"></i>':'' !!}</a></li>
                                <li><a href="{{ route('newcomer.team') }}">Équipe{!! Auth::user()->isPageChecked('team')?' <i class="fa fa-check" aria-hidden="true"></i>':'' !!}</a></li>
                                <li><a href="{{ route('newcomer.backtoschool') }}">Partenaires{!! Auth::user()->isPageChecked('backtoschool')?' <i class="fa fa-check" aria-hidden="true"></i>':'' !!}</a></li>
                                <li><a href="{{ route('newcomer.wei') }}">Week-End{!! Auth::user()->isPageChecked('wei')?' <i class="fa fa-check" aria-hidden="true"></i>':'' !!}</a></li>
                            </ul>
                            <ul class="nav navbar-nav navbar-right">
                                @if(Auth::user()->team_id != null)
                                    <li class="dropdown">
                                        <a class="dropdown-toggle" data-toggle="dropdown" role="button" href="">Défis <span class="caret"></span></a>
                                        <ul class="dropdown-menu">
                                            <li><a href={{ route('challenges.list') }}>Accéder à la liste des défis</a></li>
                                            <li><a href={{ route("challenges.sent") }}>Défis relevés </a></li>
                                            {{-- <li><a href="{{ route("challenges.faction_leaderboard") }}">Classement des factions</a></li> --}}
                                        </ul>
                                    </li>
                                @endif
                                <li><a href="{{ route('newcomer.deals') }}" title="Bons plans"><i class="fa fa-thumbs-up" aria-hidden="true"></i><span class="hidden-sm"> Bons plans</span></a></li>
                                <li><a href="{{ route('newcomer.faq') }}" title="FAQ"><i class="fa fa-question-circle" aria-hidden="true"></i><span class="hidden-sm"> FAQ</span></a></li>
                                <li><a href="{{ route('contact') }}" title="Nous contacter"><i class="fa fa-envelope" aria-hidden="true"></i><span class="hidden-sm"> Nous contacter</span></a></li>
                                <li><a href="{{ route('newcomer.auth.logout') }}" title="Se déconnecter"><i class="fa fa-power-off" aria-hidden="true"></i><span class="hidden-sm"> Déconnexion</span></a></li>
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
                        <div class="box box-default">
                            <div class="box-header with-border">
                                <h3 class="box-title">Es-tu prêt pour l'intégration ?</h3>
                                <span class="pull-right">Prochaine action : <a href="{{{ route('newcomer.'.Auth::user()->getNextCheck()['page']) }}}">{{{ Auth::user()->getNextCheck()['action'] }}}</a></span>

                            </div>
                            <div class="progress text-center" style="margin-bottom:0;">
                                <div class="progress-bar progress-bar progress-bar-striped active" role="progressbar" aria-valuenow="{{{ Auth::user()->getChecklistPercent() }}}" aria-valuemin="0" aria-valuemax="100" style="width: {{{ Auth::user()->getChecklistPercent() }}}%">
                                    {{{ Auth::user()->getChecklistPercent() }}}% <span class="sr-only">{{{ Auth::user()->getChecklistPercent() }}}% Complete (success)</span>
                                </div>
                                @if(Auth::user()->getChecklistPercent() == 0)
                                    <small>0%</small>
                                @endif
                            </div>
                        </div>
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


        </div>
    </div>
@endsection
