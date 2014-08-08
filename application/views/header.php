<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8" />

	<title>PhotoGallery</title>
	<meta name="description" content="">
	<meta name="author" content="">

	<meta name="viewport" content="width=device-width, initial-scale=1.0">

	<link href="<?php echo BASE_URL; ?>static/css/bootstrap.min.css" rel="stylesheet" media="screen">

	<link href="<?php echo BASE_URL; ?>static/css/bootstrap-responsive.css" rel="stylesheet">

	<link href="<?php echo BASE_URL; ?>static/css/prettyPhoto.css" rel="stylesheet">

	<link href="<?php echo BASE_URL; ?>static/css/font-awesome.min.css" rel="stylesheet">

	<link rel="stylesheet" href="<?php echo BASE_URL; ?>static/css/style.css?v=2" type="text/css" media="screen" />

	<link href='http://fonts.googleapis.com/css?family=Asap:400,400italic,700,700italic' rel='stylesheet' type='text/css'>

	<script src="<?php echo BASE_URL; ?>static/js/jquery-1.9.0.min.js"></script>

</head>

<body>

	<div class="container">

		<header>
			<div class="page-header">
				<a href="<?php echo BASE_URL; ?>"><h1>PhotoGallery</h1></a>
			</div>
		</header>

		<div class="row">

			<?php if($isUserAdmin) { ?>

				<div class="span9">

			<?php } else { ?>

				<div class="span12">

			<?php } ?>

				<section>