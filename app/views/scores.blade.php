<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <title>Tableau récapitulatif des scores</title>

    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <link href="//maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.min.css" rel="stylesheet">
    <link href="//maxcdn.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.min.css" rel="stylesheet" type="text/css" />
    <link href="//code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css" rel="stylesheet" type="text/css" />
    <link href="{{ asset('/css/AdminLTE.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('/css/skins/skin-blue.min.css') }}" rel="stylesheet" type="text/css" />
    @yield('css')
    <!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
        <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
</head>

<body class="skin-blue layout-top-nav">
    <div class="wrapper">
        <header class="main-header">
            <nav class="navbar navbar-static-top">
                <div class="container">
                    <div class="navbar-header">
                        <span class="navbar-brand"><b>Intégration</b> UTT</span>
                    </div>
                </div>
            </nav>
        </header>
        <div class="content-wrapper">
            <div class="container">
                <section class="content-header">
                    <h1>
                        Factions
                        <small>Tableau récapitulatif des scores</small>
                    </h1>
                </section>

                <section class="content">
                    @foreach ($factions as $faction)
                        <div class="box box-default">
                            <div class="box-header with-border">
                                <h3 class="box-title">{{ $faction->name }}</h3><h3 class="box-title pull-right">TOTAL : {{ $faction->teams->sum('points') }}</h3>
                            </div>
                            <div class="box-body table-responsive no-padding">
                                <table class="table table-hover">
                                    <tr>
                                        <th>Équipe</th>
                                        <th>Points</th>
                                    </tr>
                                    @foreach($faction->teams as $team)
                                        <tr>
                                            <td style="width:50%">{{{ $team->name }}}</td>
                                            <td style="width:50%">
                                                <b>{{ $team->points }} points</b>
                                            </td>
                                        </tr>
                                    @endforeach
                                </table>
                            </div>
                        </div>
                    @endforeach

                    </form>
                </section>
            </div>
        </div>
        <footer class="main-footer">
            <div class="container">
                <div class="hidden-xs text-center">
                    <h3>Que la meilleure faction gagnge !</h3>
                </div>
            </div>
        </footer>
    </div>

    <script src="//code.jquery.com/jquery-1.11.3.min.js"></script>
    <script src="//maxcdn.bootstrapcdn.com/bootstrap/3.3.5/js/bootstrap.min.js"></script>
</body>

</html>
