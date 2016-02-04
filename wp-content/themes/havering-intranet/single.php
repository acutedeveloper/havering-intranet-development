<?php get_header(); ?>

  <div class="three-quarters-grid">
  	<div class="left-column-home">

      <?php

      $current_page = get_queried_object();

      $terms = wp_get_post_terms( $current_page->ID, $current_page->post_type.'_tax' );

      $hi_query = 0;

      if($terms && $terms[0]->parent != 0)
      {
        $args = array($terms[0]->taxonomy => $terms[0]->slug, 'orderby' => 'menu_order', 'order' => 'asc' );
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

      <div id="subpage-nav" class="two-up-grid">

        <?php if ( $hi_query->have_posts() ) : while ( $hi_query->have_posts() ) : $hi_query->the_post(); ?>

          <?php if($count == 0): ?><div class="grid-item"><ul><?php endif; ?>

          <li><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></li>

          <?php if($count == round($total_posts/2)-1): ?></div><div class="grid-item"><ul><?php endif; ?>

          <?php if($count == $total_posts-1): ?></ul></div><?php endif; ?>

        <?php $count++; endwhile; endif; ?>
        <?php rewind_posts(); ?>
      </div>

<?php } ?>

      <?php

      $args = array('post_type' => $current_page->post_type, 'page_id' => $current_page->ID );

      query_posts( $args );

       ?>
      <article>
        <?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>
        	<h1><?php the_title(); ?></h1>

          <time datetime="<?php echo get_the_date('Y-d-jTh:i:s'); ?>">Posted on: <?php echo get_the_date(); ?></time>
          <?php the_content(); ?>

        <?php endwhile; else : ?>
        	<p><?php _e( 'Sorry, no posts matched your criteria.' ); ?></p>
        <?php endif; ?>
      </article>

    </div>

    <div class="right-column">

      <?php dynamic_sidebar( 'news_sidebar' ); ?>

    </div>
  </div>

<?php get_footer(); ?>
