<html>

<head>
    <title>Intégration UTT</title>
    <meta charset="utf-8">
    <link rel="stylesheet" href="{{ @asset('css/bootstrap.min.css') }}" media="screen">

    <meta name="apple-mobile-web-app-title" content="Intégration UTT">
    <meta name="msapplication-TileColor" content="#3c8dbc">
    <meta name="application-name" content="Intégration UTT">

    <link rel="icon" href="{{ @asset('favicon.ico') }}" />
    <link rel="apple-touch-icon" sizes="120x120" href="{{ @asset('img/icons/apple-touch-icon-120x120.png') }}">
    <link rel="apple-touch-icon" sizes="114x114" href="{{ @asset('img/icons/apple-touch-icon-114x114.png') }}">
    <link rel="apple-touch-icon" sizes="76x76" href="{{ @asset('img/icons/apple-touch-icon-76x76.png') }}">
    <link rel="apple-touch-icon" sizes="72x72" href="{{ @asset('img/icons/apple-touch-icon-72x72.png') }}">
    <link rel="apple-touch-icon" sizes="60x60" href="{{ @asset('img/icons/apple-touch-icon-60x60.png') }}">
    <link rel="apple-touch-icon" sizes="57x57" href="{{ @asset('img/icons/apple-touch-icon-57x57.png') }}">
    <link rel="icon" type="image/png" href="{{ @asset('img/icons/favicon-16x16.png') }}" sizes="16x16">
    <link rel="icon" type="image/png" href="{{ @asset('img/icons/favicon-32x32.png') }}" sizes="32x32">
    <link rel="icon" type="image/png" href="{{ @asset('img/icons/favicon-96x96.png') }}" sizes="96x96">

    <style media="screen">
        .panel {
            text-align: center;
        }
    </style>
    <body>
        <div class="container">
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
                                <a class="form-control btn btn-danger" href="{{ route('referrals.destroy') }}">Supprimer mon compte et me déconnecter</a>
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
    </body>

</html>
