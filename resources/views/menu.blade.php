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
                            <h3 class="panel-title">Formulaire de parrainge</h3>
                        </div>
                        <div class="panel-body">
                            Remplis tes informations en cliquant sur le bouton ci-desssous et deviens parrain !<br>
                            Tu seras contacté par tes fillots dans l'été !
                            @if (!$student->referral)
                                <br><br><b>TU N'ES PAS ENCORE INSCRIT !</b>
                                <br><br>
                                <a href="{{ route('referrals.edit') }}" class="btn form-control btn-success">Go !</a>
                            @elseif ($student->isValidatedReferral())
                                <br><br><b>TON PROFIL A ÉTÉ VALIDÉ PAR L'ORGA, TU NE PEUX PLUS MODIFIER TES INFORMATIONS !</b>
                            @else
                                <br><br>
                                <a href="{{ route('referrals.edit') }}" class="btn form-control btn-success">Go !</a>
                            @endif
                        </div>
                    </div>

                @if (!$student->isValidatedReferral() && $student->referral)
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <h3 class="panel-title">Ne plus être parrain</h3>
                        </div>
                        <div class="panel-body">
                            Si tu as décidé de te retirer de ton rôle de parrain, clique sur le bouton ci-dessous.<br><br>
                            <a class="form-control btn btn-danger" href="{{ route('referrals.destroy') }}">Ne plus être parrain</a>
                        </div>
                    </div>
                @endif

                @if ($student->hasDashboard())
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <h3 class="panel-title">Administration</h3>
                        </div>
                        <div class="panel-body">
                            Validation des messages, générer les PDF, ...<br><br>
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
