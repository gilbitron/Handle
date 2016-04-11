@extends('layout')

@section('content')
	<header class="major container 75%">
		<h2>Handle is a blazing fast static site generator powered by PHP and the command line.</h2>
	</header>

	<div class="box alt container">
		<section class="feature left">
			<a href="#" class="image icon fa-code"><img src="themes/directive/images/pic01.jpg" alt="" /></a>
			<div class="content">
				<h3>Powered by the Command Line</h3>
				<p>Made for developers, Handle enables flexible content generation and deployments using the CLI. You don't even need to run PHP on your deployment server!</p>
			</div>
		</section>
		<section class="feature right">
			<a href="#" class="image icon fa-pencil"><img src="themes/directive/images/pic02.jpg" alt="" /></a>
			<div class="content">
				<h3>Easy Markdown Content Editing</h3>
				<p>Markdown is the new standard for content editing, and flat files (i.e. the lack of a database) makes your Handle website simple to run and ultra performant (it's just HTML after all).</p>
			</div>
		</section>
		<section class="feature left">
			<a href="#" class="image icon fa-file-code-o"><img src="themes/directive/images/pic03.jpg" alt="" /></a>
			<div class="content">
				<h3>Powerful Blade Templating</h3>
				<p>Blade templating (created for the popular Laravel framework) makes creating themes for your Handle website easy and very flexbile. Creating websites has never been easier!</p>
			</div>
		</section>
	</div>

	<footer class="major container 75%">
		<h3>Get Started</h3>
		<p>Get your Handle site up and running in a matter of minutes.</p>
		<ul class="actions">
			<li><a href="https://github.com/gilbitron/Handle" class="button">Download & Docs</a></li>
		</ul>
	</footer>
@endsection