<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <title>Intégration UTT</title>

    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <link href="//maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.min.css" rel="stylesheet">
    <link href="//maxcdn.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.min.css" rel="stylesheet" type="text/css" />
    <link href="//code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css" rel="stylesheet" type="text/css" />
    <link href="{{ asset('/css/AdminLTE.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('/css/skins/skin-blue.min.css') }}" rel="stylesheet" type="text/css" />
    <!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
        <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
</head>

<body class="skin-blue layout-top-nav">
    <div class="wrapper">
        <header class="main-header">
            @include('dashboard.navbar')
        </header>

        <div class="content-wrapper">
            <div class="container">
                <section class="content-header">
                    @yield('title')
                </section>

                <section class="content">
                    @yield('content')
                </section>
            </div>
        </div>

        <footer class="main-footer">
            <div class="container">
                <div class="pull-right hidden-xs">
                    <b>Version</b> 0.0.1
                </div>
                <strong>En cas de problème,</strong> contacter <a href="mailto:thomas@chauchefoin.fr">flan</a> (pas trop non plus hein) (non mais c'est censé marcher) (t'as rebooté ?).
            </div>
        </footer>
    </div>

    <script src="//code.jquery.com/jquery-1.11.3.min.js"></script>
    <script src="//maxcdn.bootstrapcdn.com/bootstrap/3.3.5/js/bootstrap.min.js"></script>
    <script src="{{ asset('js/admin.min.js') }}" type="text/javascript"></script>
</body>

</html>
