<?php 
	/**
	*	Custom *.css files.
	*/
	$styles = array('starter-template');


	/**
	*	Custom body class
	*/
	$bodyclass = array(); 

	include('common/_header.php');
?>
		<!-- PAGE CONTENT -->
		<div class="container">
			<div class="starter-template">
				<h1>Bootstrap starter template</h1>
				<p class="lead">Use this document as a way to quickly start any new project.<br><a href="#" data-toggle="tooltip" title="first tooltip">Hover over me</a>! All you get is this text and a mostly barebones HTML document.</p>
			</div>
		</div>
		<!-- EOF PAGE CONTENT -->
	<script>
		$(function () {
			App.initialize();
		});
	</script>
<?php
	include('common/_footer.php');
?>