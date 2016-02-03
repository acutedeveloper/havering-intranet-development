<!DOCTYPE html>
<html class="no-js" lang="">
	<head>
    <meta charset="utf-8">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <title></title>
    <meta name="description" content="">
    <meta name="viewport" content="width=device-width, initial-scale=1">

		<link rel="stylesheet" href="<?php bloginfo('stylesheet_directory'); ?>/library/css/main.css" media="all" />
		<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.5.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="<?php bloginfo('stylesheet_directory'); ?>/library/js/bxslider/jquery.bxslider.css" media="all" />

		<?php wp_head(); ?>
	</head>
	<body class="body">
    <div class="wrapper">

      <div class="header-search">
      	<form action="#" method="post" class="inline-form search-form">
      	  <div class="inline-container">
      	      <input type="search" placeholder="Search" id="search-field" class="search-field" />
      		    <button class="btn btn-information">
      		    	Search
      		    </button>
      	  </div>
      	</form>
      </div>
      <header id="header" class="cf" role="banner">
      	<div class="container">
      		<div class="logo">
      			<a href="<?php echo site_url(); ?>"><img src="<?php bloginfo('stylesheet_directory'); ?>/library/images/logo-havering-intranet.png" alt="Havering Intranet" /></a>
      		</div>
      		<ul class="bxslider">
      			<li><a href="#"><img src="<?php bloginfo('stylesheet_directory'); ?>/library/images/carousel-yammer.gif" alt="Yammer" /></a></li>
      			<li><a href="#"><img src="<?php bloginfo('stylesheet_directory'); ?>/library/images/carousel-password-safe.gif" alt="Password Safe" /></a></li>
      			<li><a href="#"><img src="<?php bloginfo('stylesheet_directory'); ?>/library/images/carousel-pants.gif" alt="Pants" /></a></li>
      		</ul>
      	</div>
      </header>

			<?php if(!is_home()): ?>
			<div id="breadcrumbs">
	    	<?php the_breadcrumb(); ?>
	    </div>
		<?php endif; ?>
