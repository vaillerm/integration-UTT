@extends('layouts.master')

@section('css')
<link href="//maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.min.css" rel="stylesheet">
<link href="//maxcdn.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.min.css" rel="stylesheet" type="text/css" />
<link href="//code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css" rel="stylesheet" type="text/css" />
<link href="{{ @asset('/css/AdminLTE.min.css') }}" rel="stylesheet" type="text/css" />
<link href="{{ @asset('/css/skins/skin-blue.min.css') }}" rel="stylesheet" type="text/css" />
@endsection

@section('title')
Connexion
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
                            <a href="{{ route('dashboard.index') }}" class="navbar-brand"><b>Intégration</b> UTT</a>
                        </div>
                        <!-- Collect the nav links, forms, and other content for toggling -->
                        <div class="collapse navbar-collapse" id="navbar-collapse">
                            <ul class="nav navbar-nav navbar-right">
                                <li><a href="{{ route('index') }}"><i class="fa fa-home" aria-hidden="true"></i> Retour</a></li>
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
                    </section>
                    <section class="content">
                        <div class="col-md-6 col-md-offset-3">
                            <div class="box box-default">
                                <div class="box-header with-border">
                                    <h3 class="box-title">Bienvenue à l'UTT !</h3>
                                </div>
                                <div class="box-body">
                                    {{-- <p>
                                        <strong>Bonjour à toi, ami nouveau !</strong> Tu trouveras les identifiants du site d'intégration sur le courrier que tu recevera entre fin juillet et début aout.
                                    </p>
                                    <p>
                                        Si tu n'as toujours pas reçu tes identifiants au 5 aout ou pour toute autre question, envoi nous un email à <a href="mailto:integration@utt.fr">integration@utt.fr</a>.
                                    </p>
                                    <form action="{{ route('newcomer.auth.login') }}" method="post">
                                        <input type="text" name="login" class="form-control" placeholder="Identifiant"/>
                                        <input type="password" name="password" class="form-control" placeholder="Mot de passe"/>
                                        <input type="submit" class="btn form-control btn-primary" value="Se connecter" />
                                    </form> --}}
                                    <p>
                                        <big>Le site n'est pas encore prêt à t'accueillir, repasse en fin de semaine :)</big>
                                    </p>
                                    <p>
                                        En attendant, pour te tenir au courant des dernières infos sur ton Intégration, tu peux suivre la page facebook !
                                    </p>
                                    <div class="text-center">
                                        <iframe src="https://www.facebook.com/plugins/page.php?href=https%3A%2F%2Fwww.facebook.com%2Fintegration.utt&tabs&width=340&height=70&small_header=true&adapt_container_width=false&hide_cover=false&show_facepile=false&appId=248954165119490" width="340" height="70" style="border:none;overflow:hidden" scrolling="no" frameborder="0" allowTransparency="true"></iframe>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </section>
                </div>
            </div>

        </div>
    </div>
@endsection
