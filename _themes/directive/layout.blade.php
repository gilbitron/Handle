<!DOCTYPE HTML>
<html>
<head>
	<title>{{ $title }}</title>
	<meta charset="utf-8" />
	<meta name="viewport" content="width=device-width, initial-scale=1" />
	<!--[if lte IE 8]><script src="_themes/directive/assets/js/ie/html5shiv.js"></script><![endif]-->
	<link rel="stylesheet" href="_themes/directive/assets/css/main.css" />
	<!--[if lte IE 8]><link rel="stylesheet" href="_themes/directive/assets/css/ie8.css" /><![endif]-->

	<script>
	if (window.location.host == "gilbitron.github.io" && window.location.protocol != 'https:') {
		window.location.protocol = 'https';
	}
	</script>
</head>
<body>

	<!-- Header -->
	<div id="header">
		<span class="logo icon fa-book"></span>
		<h1>Handle</h1>
		<p>A static site generator powered by PHP and the command line.</p>
	</div>

	<!-- Main -->
	<div id="main">

		@yield('content')

	</div>

	<!-- Footer -->
	<div id="footer">
		<div class="container 75%">

			<header class="major last"></header>

			<a class="github-button" href="https://github.com/gilbitron/Handle" data-icon="octicon-star" data-style="mega" data-count-href="/gilbitron/Handle/stargazers" data-count-api="/repos/gilbitron/Handle#stargazers_count" data-count-aria-label="# stargazers on GitHub" aria-label="Star gilbitron/Handle on GitHub">Star</a>
			<br><br>

			<ul class="icons">
				<li><a href="https://twitter.com/gilbitron" class="icon fa-twitter"><span class="label">Twitter</span></a></li>
				<li><a href="https://github.com/gilbitron" class="icon fa-github"><span class="label">Github</span></a></li>
			</ul>

			<ul class="copyright">
				<li>Handle was created by <a href="http://gilbert.pellegrom.me/">Gilbert Pellegrom</a> from
					<a href="http://dev7studios.com/">Dev7studios</a>.<br>
					Released under the MIT license.</li>
			</ul>

		</div>
	</div>

	<!-- Scripts -->
	<script src="_themes/directive/assets/js/jquery.min.js"></script>
	<script src="_themes/directive/assets/js/skel.min.js"></script>
	<script src="_themes/directive/assets/js/util.js"></script>
	<!--[if lte IE 8]><script src="_themes/directive/assets/js/ie/respond.min.js"></script><![endif]-->
	<script src="_themes/directive/assets/js/main.js"></script>

	<script async defer id="github-bjs" src="https://buttons.github.io/buttons.js"></script>

</body>
</html>