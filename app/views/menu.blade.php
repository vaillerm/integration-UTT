<html>

<head>
    <title>Intégration UTT</title>
    <meta charset="utf-8">
    <link rel="stylesheet" href="{{ asset('css/bootstrap.min.css') }}" media="screen">
    <style media="screen">
        .panel {
            text-align: center;
        }
    </style>
    <body>
        <div class="container">
            <div class="text-center">
                <h1>Intégration 2015</h1>
            </div>
            <div class="row">
                <div class="col-md-8 col-md-offset-2">

                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <h3 class="panel-title">Formulaire de parrainge</h3>
                        </div>
                        <div class="panel-body">
                            Remplis tes informations en cliquant sur le bouton ci-desssous et deviens parrain !<br>
                            Tu seras contacté par tes fillots dans l'été !
                            @if (Referral::find(Session::get('student_id')) == false)
                                <br><br><b>TU N'ES PAS ENCORE INSCRIT !</b>
                                <br><br>
                                <a href="{{ route('referrals.edit') }}" class="btn form-control btn-success">Go !</a>
                            @elseif (Referral::find(Session::get('student_id'))->validated)
                                <br><br><b>TON PROFIL A ÉTÉ VALIDÉ PAR L'ORGA, TU NE PEUX PLUS MODIFIER TES INFORMATIONS !</b>
                            @else
                                <br><br>
                                <a href="{{ route('referrals.edit') }}" class="btn form-control btn-success">Go !</a>
                            @endif
                        </div>
                    </div>

                    @if (Referral::find(Session::get('student_id')) && Referral::find(Session::get('student_id'))->validated == 0)
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <h3 class="panel-title">Ne plus être parrain</h3>
                        </div>
                        <div class="panel-body">
                            Si tu as décidé de te retirer de ton rôle de parrain, clique sur le bouton ci-dessous.<br>
                            Cette action est réversible mais tu auras perdu toutes les informations que tu as indiqué.<br><br>
                            <a class="form-control btn btn-danger" href="{{ route('referrals.destroy') }}">Supprimer mon compte et me déconnecter</a>
                        </div>
                    </div>
                    @endif

                    @if (DB::table('administrators')->where('student_id', Session::get('student_id'))->first())
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
