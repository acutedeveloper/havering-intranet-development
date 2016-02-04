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
			//printme($current_category);

			$args = array('paged' => $paged, $current_category->taxonomy => $current_category->slug, 'orderby' => 'menu_order', 'order' => 'asc' );

			$hi_query = new WP_Query( $args );

			// Count the total Posts
			$total_posts = count($hi_query->posts);

			$count = 0;

			global $wp_query;
			// Put default query object in a temp variable
			$tmp_query = $wp_query;
			// Now wipe it out completely
			$wp_query = null;
			// Re-populate the global with our custom query
			$wp_query = $hi_query;

    ?>

		<div class="three-quarters-grid">
		  <div class="left-column">

				<h1><?php echo $current_category->name ?></h1>

				<div id="subpage-nav" class="two-up-grid">

					<?php while ( $hi_query->have_posts() ) : $hi_query->the_post(); ?>

						<?php if($count == 0): ?><div class="grid-item"><ul><?php endif; ?>

						<li><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></li>

						<?php if($count == round($total_posts/2)-1): ?></div><div class="grid-item"><ul><?php endif; ?>

						<?php if($count == $total_posts-1): ?></ul></div><?php endif; ?>

					<?php $count++; endwhile; ?>

				</div>

		    <article>
		      <?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>
		        <h1><?php the_title(); ?></h1>

		        <?php the_excerpt(); ?>

		      <?php endwhile; else : ?>
		        <p><?php _e( 'Sorry, no posts matched your criteria.' ); ?></p>
		      <?php endif; ?>
		    </article>

		  </div>

		  <div class="right-column">

		    <?php dynamic_sidebar( 'content_sidebar' ); ?>


		  </div>
		</div>

<?php get_footer(); ?>
