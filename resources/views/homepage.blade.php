<!-- Import a special layout for the homepage -->
@extends('layouts.master2')

<!--Modify page title-->
@section('title')
    Accueil
@endsection

@section('css')
    <!-- Bootstrap Core CSS -->
    <link href="{{ asset('front/bootstrap/css/bootstrap.min.css') }}" rel="stylesheet">
    <link href="{{ asset('css/bootstrap.min.css') }}" rel="stylesheet" media="screen">
    <link href="{{ asset('css/flipclock.css') }}" rel="stylesheet">

    <!-- Custom Fonts -->
    <link href="{{ asset('front/font-awesome/css/font-awesome.min.css') }}" rel="stylesheet" type="text/css">
    <link href="https://fonts.googleapis.com/css?family=Lora:400,700,400italic,700italic" rel="stylesheet" type="text/css">
    <link href="https://fonts.googleapis.com/css?family=Montserrat:400,700" rel="stylesheet" type="text/css">

    <!-- Theme CSS -->
    <link href="{{ asset('css/grayscale.min.css') }}" rel="stylesheet">
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

@section('bodycontent')
    <!-- Navigation -->
    <nav class="navbar navbar-custom navbar-fixed-top" role="navigation">
        <div class="container">
            <div class="navbar-header">
                <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-main-collapse">
                    Menu <i class="fa fa-bars"></i>
                </button>
                <a class="navbar-brand page-scroll" href="#page-top">
                    <i class="fa fa-play-circle"></i><span class="light">Rejoins</span> l'Intégration
                </a>
            </div>

            <!-- Collect the nav links, forms, and other content for toggling -->
            <div class="collapse navbar-collapse navbar-right navbar-main-collapse">
                <ul class="nav navbar-nav">
                    <!-- Hidden li included to remove active class from about link when scrolled up past about section -->
                    <li class="hidden">
                        <a href="#page-top"></a>
                    </li>
                    <li>
                        <a class="page-scroll" href="#about">C'est quoi ?</a>
                    </li>
                    <li>
                        <a class="page-scroll" href="#sponsor">Partenaires</a>
                    </li>
                    <li>
                        <a class="page-scroll" href="#contact">Contact</a>
                    </li>
                </ul>
            </div>
            <!-- /.navbar-collapse -->
        </div>
        <!-- /.container -->
    </nav>

    <!-- Intro Header -->
    <header class="intro">
        <div class="intro-body">
            <div class="container">
                <div class="row">
                    <div class="col-md-8 col-md-offset-2">
                        <h1 class="brand-heading">Intégration UTT</h1>
                        <p class="intro-text">Contes & Mythology</p>
							<br/><br/>
                            @if(Config::get('services.site.loginOpen') === '1')
                                <a href="{{ route('newcomer.auth.login') }}" class="btn btn-top">Je suis nouveau !</a><br/>
                                <a href="{{ route('menu') }}" class="btn btn-top">Je suis étudiant à l'UTT</a>
                            @else
                                <a class="btn btn-top">Tu pourra bientôt t'inscrire sur le site, patience jeune héro....</a>
                            @endif
							<br/><br/>
						<a href="#about" class="btn btn-circle page-scroll">
                            <i class="fa fa-angle-double-down animated"></i>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </header>

    <!-- About Section -->
    <section id="about" class="container content-section text-center">
        <div class="row">
            <div class="col-lg-8 col-lg-offset-2">
                <h1>C'est quoi l'Inté?</h1>
                <p>Pour sa 21ème édition, l'intégration verra s'affronter dans un combat épique dieux tout puissant, héros légendaires et bêtes des temps anciens, au sein de la mythique Ellipse de l'UTT.</p>
                <p>Que vous soyez un valeureux nouveau, un chef d'équipe intrépide ou un orga agueri, vous devrez choisir votre camps et entrer dans la bataille! Serez vous pret? Nous le verrons le <em>4 Septembre 2017</em>...</p>
            <br/><br/><br/>
                <h3>Info Pratiques</h3>
                <p>Il n'y en a pas. De toute façon l'inté c'est pas pratique ;)</p>
            </div>
        </div>
    </section>

    <!-- Sponsor Section -->
    <section id="wei" class="content-section text-center">
        <div class="wei-section">
            <div class="container">
                <div class="col-lg-8 col-lg-offset-2">
                    <h1>Inscription au Wei</h1>
                        @if(Config::get('services.wei.open') === '0')
                            <p>Les inscriptions pour le WEI ne sont pas encore ouvertes!</p>
                        @else
                            @if(((new DateTime(Config::get('services.wei.registrationStart')))->diff(new DateTime()))->invert)
                                <p>Ouverture des inscriptions pour le weekend dans :</p>
                                <br/><br/>
                                <div class="countdown hidden-xs" style="width:640px;margin:20px auto;"></div>
                                <p><big class="visible-xs">{{ ((new DateTime(Config::get('services.wei.registrationStart')))->diff(new DateTime()))->format('%d jrs %h hrs %i min et %s sec') }}</big></p>
                            @else
                                <br/><br/>
                                <p>Les inscriptions pour le weekend sont ouvertes !</p>
                            @endif
                        @endif
                </div>
            </div>
        </div>
    </section>

    <!-- Contact Section -->
    <section id="sponsor" class="container content-section text-center">
        <div class="row">
            <div class="col-lg-12 col-lg-offset-0">
                <div class="sponsor">
                    <h1>Nos Partenaires</h1>
                        <?php
                            $sponsors = [];
                            $sponsors[] = [ 'link' => 'http://www.accesbureautique.fr/', 'img' => asset("img/sponsors/access.png"), 'alt' => 'Access Bureautique' ];
                            $sponsors[] = [ 'link' => 'http://www.ada.fr/location-voiture-troyes.html', 'img' => asset("img/sponsors/ada.png"), 'alt' => 'ADA Location de véhicules' ];
                            $sponsors[] = [ 'link' => 'http://www.beijaflore.com/', 'img' => asset("img/sponsors/beijaflore.png"), 'alt' => 'Beijaflore' ];
                            $sponsors[] = [ 'link' => 'http://www.yves-damonte.fr', 'img' => asset("img/sponsors/damonte.png"), 'alt' => 'Damonte Immobilier' ];
                            $sponsors[] = [ 'link' => 'http://www.memphis-coffee.com/memphis-coffee-troyes', 'img' => asset("img/sponsors/memphis.png"), 'alt' => 'Memphis Coffe' ];
                            $sponsors[] = [ 'link' => 'http://www.mgel.fr/', 'img' => asset("img/sponsors/mgel.png"), 'alt' => 'MGEL' ];
                            $sponsors[] = [ 'link' => 'http://www.auto-ecole-popeye.fr/', 'img' => asset("img/sponsors/popeye.png"), 'alt' => 'Popeye auto-école' ];
                            shuffle($sponsors);
                        ?>
                        @foreach($sponsors as $sponsor)
                                <a href="{{{ $sponsor['link'] }}}"><img src="{{{ $sponsor['img'] }}}" alt="{{{ $sponsor['alt'] }}}" /></a>
                        @endforeach
                </div>
            </div>
        </div>
    </section>
    <br/><br/><br/><br/>
    <section id="contact" class="container content-section text-center">
        <div class="row">
            <div class="col-lg-8 col-lg-offset-2">
                <div class="text-center">
                    <h1>Contactez Nous</h1>
                        <p>Vous souhaitez participer à l'Intégration en tant que partenaire, bénévole ou bien vous arrivez à l'UTT à la rentrée? N'hesitez pas à nous contacter!</p>
                        <p><a href="mailto:feedback@startbootstrap.com">integration@utt.fr</a></p>
                    <ul class="list-inline banner-social-buttons">
                        <li>
                            <a href="https://twitter.com/IntegrationUTT" class="btn btn-default btn-lg"><i class="fa fa-twitter fa-fw"></i> <span class="network-name">Twitter</span></a>
                        </li>
                        <li>
                            <a href="https://facebook.com/integration.utt" class="btn btn-default btn-lg"><i class="fa fa-facebook fa-fw"></i> <span class="network-name">Facebook</span></a>
                        </li>
                        <!-- <li> -->
                            <!-- <a href="https://plus.google.com/+Startbootstrap/posts" class="btn btn-default btn-lg"><i class="fa fa-google-plus fa-fw"></i> <span class="network-name">Google+</span></a> -->
                        <!-- </li> -->
                    </ul>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer>
        <div class="container text-center">
            <br/>
            <p>Copyright &copy; Intégration UTT 2017</p>
            <br/>
        </div>
    </footer>
@endsection
