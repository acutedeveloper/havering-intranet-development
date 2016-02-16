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

      	<form action="<?php echo site_url(); ?>" method="get" class="inline-form search-form">
      	  <div class="inline-container">
      	      <input type="search" placeholder="Search" id="search-field" class="search-field" name="s" id="s" />
      		    <button class="btn btn-information">
      		    	Search
      		    </button>
      	  </div>
      	</form>
      </div>

				<?php

					// To get carousel items page
					$args = array (
						'p'                      => '1734',
						'post_type'              => array( 'page' ),
					);

					// The Query
					$query = new WP_Query( $args );

				?>

	      <header id="header" class="cf" role="banner">
      	<div class="container">
      		<div class="logo">
      			<a href="<?php echo site_url(); ?>"><img src="<?php bloginfo('stylesheet_directory'); ?>/library/images/logo-havering-intranet.png" alt="Havering Intranet" /></a>
      		</div>
					<?php while ($query->have_posts()) : $query->the_post(); ?>
      		<ul class="bxslider">
					<?php
						if( have_rows('carousel_images') ): while ( have_rows('carousel_images') ) : the_row();
							$image = get_sub_field('carousel_image');
					?>
      			<li><a href="<?php echo get_sub_field('carousel_link'); ?>"><img src="<?php echo $image['url']; ?>" alt="<?php echo $image['name']; ?>" /></a></li>
					<?php endwhile; endif; ?>
      		</ul>
				<?php endwhile; wp_reset_query(); rewind_posts(); ?>
      	</div>
      </header>

			<?php if(!is_home()): ?>
			<div id="breadcrumbs">
	    	<?php the_breadcrumb(); ?>
	    </div>
		<?php endif; ?>
