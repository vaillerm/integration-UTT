<!DOCTYPE html>
<html>
	<head>
	    <meta charset="UTF-8">
	    <title>@yield('title') - Intégration UTT</title>

	    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">

	    <meta name="apple-mobile-web-app-title" content="Intégration UTT">
	    <meta name="msapplication-TileColor" content="#3c8dbc">
	    <meta name="application-name" content="Intégration UTT">

	    <link rel="icon" href="{{ asset('favicon.ico') }}" />
	    <link rel="apple-touch-icon" sizes="120x120" href="{{ asset('img/icons/apple-touch-icon-120x120.png') }}">
	    <link rel="apple-touch-icon" sizes="114x114" href="{{ asset('img/icons/apple-touch-icon-114x114.png') }}">
	    <link rel="apple-touch-icon" sizes="76x76" href="{{ asset('img/icons/apple-touch-icon-76x76.png') }}">
	    <link rel="apple-touch-icon" sizes="72x72" href="{{ asset('img/icons/apple-touch-icon-72x72.png') }}">
	    <link rel="apple-touch-icon" sizes="60x60" href="{{ asset('img/icons/apple-touch-icon-60x60.png') }}">
	    <link rel="apple-touch-icon" sizes="57x57" href="{{ asset('img/icons/apple-touch-icon-57x57.png') }}">
	    <link rel="icon" type="image/png" href="{{ asset('img/icons/favicon-16x16.png') }}" sizes="16x16">
	    <link rel="icon" type="image/png" href="{{ asset('img/icons/favicon-32x32.png') }}" sizes="32x32">
	    <link rel="icon" type="image/png" href="{{ asset('img/icons/favicon-96x96.png') }}" sizes="96x96">

	    @yield('sublayout-css')
	    @yield('css')
	    <!--[if lt IE 9]>
	        <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
	        <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
	    <![endif]-->

	    <link href="{{ asset('/css/style.css') }}" rel="stylesheet" type="text/css" />

	</head>
	<body>
		@yield('bodycontent')

	    <script src="//code.jquery.com/jquery-1.11.3.min.js"></script>
	    <script src="//maxcdn.bootstrapcdn.com/bootstrap/3.3.5/js/bootstrap.min.js"></script>
	    <script src="{{ asset('js/admin.min.js') }}" type="text/javascript"></script>
	    @yield('js')
	    @yield('sublayout-js')

		<!-- Matomo -->
		<script type="text/javascript">
			var _paq = window._paq || [];
			/* tracker methods like "setCustomDimension" should be called before "trackPageView" */
			_paq.push(["setCookieDomain", "*.integration.utt.fr"]);
			_paq.push(['trackPageView']);
			_paq.push(['enableLinkTracking']);
			(function() {
				var u="//piwik.uttnetgroup.fr/";
				_paq.push(['setTrackerUrl', u+'matomo.php']);
				_paq.push(['setSiteId', '5']);
				var d=document, g=d.createElement('script'), s=d.getElementsByTagName('script')[0];
				g.type='text/javascript'; g.async=true; g.defer=true; g.src=u+'matomo.js'; s.parentNode.insertBefore(g,s);
			})();
		</script>
		<noscript><p><img src="//piwik.uttnetgroup.fr/matomo.php?idsite=5&amp;rec=1" style="border:0;" alt="" /></p></noscript>
		<!-- End Matomo Code -->

		<!-- END Global site tag (gtag.js) - Google Analytics -->
	</body>
</html>
