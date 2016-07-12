@extends('layouts.master')

@section('title')
Menu
@endsection

@section('css')
<link rel="stylesheet" href="{{ @asset('css/bootstrap.min.css') }}" media="screen">
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
                            @if ($referralDeadline['open'])
                                @if (!$student->referral)
                                    <strong>Fermeture dans {{ $referralDeadline['days'] }}j {{ $referralDeadline['hours'] }}h {{ $referralDeadline['minutes'] }}min</strong>
                                    <br/><br/>
                                    <a href="{{ route('referrals.edit') }}" class="btn form-control btn-success">Devenir parrain ou marraine !</a>
                                @elseif ($student->isValidatedReferral())
                                    <strong>TON PROFIL A ÉTÉ VALIDÉ PAR L'ORGA, TU NE PEUX PLUS MODIFIER TES INFORMATIONS !</strong>
                                @else
                                    <strong>Fermeture dans {{ $referralDeadline['days'] }}j {{ $referralDeadline['hours'] }}h {{ $referralDeadline['minutes'] }}min</strong>
                                    <br/><br/>
                                    <a href="{{ route('referrals.edit') }}" class="btn form-control btn-success">Modifier mon profil !</a>
                                    <a class="form-control btn btn-danger" href="{{ route('referrals.destroy') }}">Ne plus être parrain</a>
                                @endif
                            @else
                                <strong>Les inscriptions sont fermées.<br/>
                                    Contactez integration@utt.fr pour toute question.</strong>
                            @endif
                        </div>
                    </div>

                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <h3 class="panel-title">Deviens chef d'équipe !</h3>
                        </div>
                        <div class="panel-body">
                            Envie d'accompagner les nouveaux dans les épreuves de l'intégration ?<br/>
                            Constitue ton équpe de 3 à 5 personnes en cliquant sur le bouton ci-desssous !
                            <br/><br/>
                            @if ($student->ce && $student->team)
                                <a href="{{ route('dashboard.index') }}" class="btn form-control btn-success">Modifier mon profil de chef d'équipe!</a>
                            @elseif ($ceDeadline['open'])
                                <strong>Fermeture dans {{ $ceDeadline['days'] }}j {{ $ceDeadline['hours'] }}h {{ $ceDeadline['minutes'] }}min</strong><br/>
                                @if ($ceDeadline['teamLeft'] > 0)
                                    <strong>Plus que {{ $ceDeadline['teamLeft'] }} équipes avant la fermeture</strong>
                                @endif
                                <br/><br/>
                                @if (!$student->ce)
                                    <a href="{{ route('dashboard.ce.firsttime') }}" class="btn form-control btn-success">Devenir chef d'équipe !</a>
                                @else
                                    <a href="{{ route('dashboard.index') }}" class="btn form-control btn-success">Modifier mon profil de chef d'équipe!</a>
                                @endif
                            @else
                                <strong>Les inscriptions sont fermées.<br/>
                                    Contactez integration@utt.fr pour toute question.</strong>
                            @endif
                        </div>
                    </div>

                    @if (!$student->ce && !$student->orga && !$student->admin)
                        <div class="panel panel-default">
                            <div class="panel-heading">
                                <h3 class="panel-title">Deviens bénévole !</h3>
                            </div>
                            <div class="panel-body">
                                Tu souhaite donner un petit coup de main pendant l'intégration ?<br/>
                                En cliquant sur ce bouton, tu recevera des emails pour te tenir au courant de l'avancement de l'intégration et des moments où nous avons besoins de bénévoles.
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
                        <a href="{{ route('oauth.logout') }}" class="active btn form-control btn-default">Se déconnecter</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
