@extends('layouts.master')

@section('title')
Menu
@endsection

@section('css')
<!-- <link rel="stylesheet" href="{{ asset('css/bootstrap.min.css') }}" media="screen"> -->
<link href="//maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.min.css" rel="stylesheet">
<link href="//maxcdn.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.min.css" rel="stylesheet" type="text/css" />
<link href="//code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css" rel="stylesheet" type="text/css" />
<!-- <link href="{{ asset('css/AdminLTE.min.css') }}" rel="stylesheet" type="text/css" /> -->
<link href="{{ asset('css/skin-blue.min.css') }}" rel="stylesheet" type="text/css" />
@endsection

@section('bodycontent')
    <div class="container text-center">
        <div class="text-center">
            <h1>Intégration UTT</h1>
        </div>
        <div class="row">
            <div class="col-md-8 col-md-offset-2">

                @if($student)

                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <h3 class="panel-title">Deviens parrain ou marraine !</h3>
                        </div>
                        <div class="panel-body">
                            Tu souhaites rencontrer et guider les nouveaux pour leur arrivée dans ce nouveau monde ?<br/>
                            Tu seras contacté par tes fillots dans l'été puis présenté à eux pendant la semaine d'intégration !
                            <br/><br/>
                            @if ($student->referral_validated)
                                <strong>Ton profil a été validé par l'orga, tu ne peux plus modifier tes informations !<br/></strong>
                                Contacte <a href="mailto:integration@utt.fr">integration@utt.fr</a> pour toute question.
                            @elseif (Authorization::can('student','referral'))
                                <strong>Fermeture dans {{ @countdown(Authorization::countdown('student','referral')) }}</strong>
                                <br/><br/>
                                <a href="{{ route('referrals.firsttime') }}" class="btn form-control btn-success">Devenir parrain ou marraine !</a>
                            @elseif (Authorization::can('referral','edit'))
                                <strong>Fermeture dans {{ @countdown(Authorization::countdown('referral','edit')) }}</strong>
                                <br/><br/>
                                <a href="{{ route('referrals.edit') }}" class="btn form-control btn-success">Modifier mon profil !</a>
                                <a class="form-control btn btn-danger" href="{{ route('referrals.destroy') }}">Ne plus être parrain</a>
                            @else
                                <strong>Les inscriptions sont fermées.</strong><br/>
                                    Contacte <a href="mailto:integration@utt.fr">integration@utt.fr</a> pour toute question.<br/>
                                    @if (Authorization::countdown('student','referral'))
                                        <strong>Ouverture dans {{ @countdown(Authorization::countdown('student','referral')) }}</strong>
                                    @endif
                            @endif
                        </div>
                    </div>

                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <h3 class="panel-title">Deviens chef d'équipe !</h3>
                        </div>
                        <div class="panel-body">
                            Envie d'accompagner les nouveaux dans les épreuves de l'intégration ?<br/>
                            Constitue ton équipe de 3 à 5 personnes en cliquant sur le bouton ci-desssous !
                            <br/><br/>
                            @if (Authorization::can('student','ce') || Authorization::can('ce','create'))
                                <strong>Fermeture dans {{ @countdown(Authorization::countdown('ce','create')) }}</strong><br/>
                                <strong>Plus que {{ $teamLeftTC }} équipes de TC et {{ $teamLeftBranch }} équipes de Branche avant fermeture</strong>
                                <br/><br/>
                                @if (Authorization::can('ce','create'))
                                    <a href="{{ route('dashboard.index') }}" class="btn form-control btn-success">Devenir chef d'équipe !</a>
                                @else
                                    <a href="{{ route('dashboard.ce.firsttime') }}" class="btn form-control btn-success">Devenir chef d'équipe !</a>
                                @endif
                            @elseif (Authorization::can('ce','inTeam'))
                                <a href="{{ route('dashboard.index') }}" class="btn form-control btn-success">Voir mon profil de chef d'équipe !</a>
                            @elseif (Authorization::can('student','inTeam'))
                                <strong>Vous ne pouvez plus créer d'équipe, le nombre maximal est atteint.</strong></br>
                                <a href="{{ route('dashboard.ce.firsttime') }}" class="btn form-control btn-success">Rejoins ton équipe !</a>
                            @else
                                @if (Authorization::countdown('ce','create'))
                                    <strong>Ouverture dans {{ @countdown(Authorization::countdown('ce','create')) }}</strong><br/>
                                @else
                                    <strong>Désolé, le nombre maximal d'équipes est atteint.</strong><br/>
                                    Si tu souhaites rejoindre ton équipe, demandes à ton chef d'équipe de t'inviter !</br>
                                @endif
                                Contacte <a href="mailto:integration@utt.fr">integration@utt.fr</a> pour toute autre question.
                            @endif
                        </div>
                    </div>

                    @if (!$student->volunteer || (!Authorization::can('ce','inTeam') && !$student->orga && !$student->admin))
                        <div class="panel panel-default">
                            <div class="panel-heading">
                                <h3 class="panel-title">Deviens bénévole !</h3>
                            </div>
                            <div class="panel-body">
                                Tu souhaite donner un petit coup de main pendant l'intégration ?<br/>
                                En cliquant sur ce bouton, tu receveras des mails pour te tenir au courant de l'avancement de l'intégration et des moments où nous avons besoins de bénévoles.
                                <br/><br/>
                                @if (!$student->volunteer)
                                    <a href="{{ route('dashboard.students.profil') }}" class="btn form-control btn-success">Devenir bénévole !</a>
                                @else
                                    <a href="{{ route('dashboard.students.profil') }}" class="btn form-control btn-success">Modifier mon profil bénévole !</a>
                                @endif
                            </div>
                        </div>
                    @endif

                    @if ($student->orga && !$student->admin)
                        <div class="panel panel-default">
                            <div class="panel-heading">
                                <h3 class="panel-title">Panneau d'organisateur</h3>
                            </div>
                            <div class="panel-body">
                                <a href="{{ route('dashboard.index') }}" class="btn form-control btn-success">Accès au panneau d'organisateur</a>
                            </div>
                        </div>
                    @endif

                    @if ($student->admin)
                        <div class="panel panel-default">
                            <div class="panel-heading">
                                <h3 class="panel-title">Administration</h3>
                            </div>
                            <div class="panel-body">
                                <a href="{{ route('dashboard.index') }}" class="btn form-control btn-success">Accès au panneau d'administration</a>
                            </div>
                        </div>
                    @endif

                @endif
                <div class="panel panel-default">
                    <div class="panel-body">
                        <a href="{{ route('oauth.logout') }}" class="btn form-control btn-default">Se déconnecter</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
