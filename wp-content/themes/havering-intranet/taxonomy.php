<?php
/*
Template Name: Archives
*/

// This page needs to show initially:
// A list of custom posttypes
// The selected posttype
// The postype menu level 1

// Everything is being feed by the wp_nav_menu

get_header(); ?>

		<?php

      $current_category = get_queried_object();

    ?>

    <div class="one-up-grid">
    	<div id="content-navigation">
					<h1>Here is the tax page</h1>

					<?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>


				  	<!-- Display the Title as a link to the Post's permalink. -->

				  	<h2><a href="<?php the_permalink(); ?>" rel="bookmark" title="Permanent Link to <?php the_title_attribute(); ?>"><?php the_title(); ?></a></h2>


				  	<!-- Display the date (November 16th, 2009 format) and a link to other posts by this posts author. -->

				  	<small><?php the_time('F jS, Y'); ?> by <?php the_author_posts_link(); ?></small>


				  	<div class="entry">
				  		<?php the_excerpt(); ?>
				  	</div>


				  	<!-- Display a comma separated list of the Post's Categories. -->

				  	<p class="postmetadata"><?php _e( 'Posted in' ); ?> <?php the_category( ', ' ); ?></p>
				  	</div> <!-- closes the first div box -->


				  	<!-- Stop The Loop (but note the "else:" - see next line). -->

				  <?php endwhile; else : ?>


				  	<!-- The very first "if" tested to see if there were any Posts to -->
				  	<!-- display.  This "else" part tells what do if there weren't any. -->
				  	<p><?php _e( 'Sorry, no posts matched your criteria.' ); ?></p>


				  	<!-- REALLY stop The Loop. -->
				  <?php endif; ?>
    	</div>
    </div>
<?php get_footer(); ?>
