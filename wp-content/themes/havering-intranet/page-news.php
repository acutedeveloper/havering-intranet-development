<?php
/**
* Template Name: news
*/
 get_header(); ?>

  <div class="three-quarters-grid">
  	<div class="left-column">

      <?php

        $paged = ( get_query_var( 'paged' ) ) ? get_query_var( 'paged' ) : 1;
        $args = array(
          'posts_per_page' => 5,
          'paged'          => $paged
        );
        query_posts($args);

        $count = 0;

        if ( have_posts() ) : while ( have_posts() ) : the_post();
        if(is_sticky()):
      ?>

      <div class="block block-hero">
          <?php
                if ( has_post_thumbnail() )
                {
                  echo '<div class="b-thumb">';
                  the_post_thumbnail();
                  echo '</div>';
                }
          ?>
        <div class="b-text">
          <h2><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
          <time datetime="<?php echo get_the_date('Y-d-jTh:i:s'); ?>">Posted on: <?php echo get_the_date(); ?></time>
          <?php the_excerpt(); ?>
          <a href="<?php the_permalink(); ?>">Read more <i class="fa fa-chevron-circle-right"></i></a>
        </div>
      </div>
    <?php else: //is_ticky ?>

      <div class="block <?php echo (has_post_thumbnail() ? 'block-thumb' : 'block-headline') ?>">
        <?php
              if ( has_post_thumbnail() )
              {
                echo '<div class="b-thumb">';
                the_post_thumbnail();
                echo '</div>';
              }
        ?>
        <div class="b-text">
          <h2><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
          <time datetime="<?php echo get_the_date('Y-d-jTh:i:s'); ?>">Posted on: <?php echo get_the_date(); ?></time>
          <?php the_excerpt(); ?>
          <a href="<?php the_permalink(); ?>">Read more <i class="fa fa-chevron-circle-right"></i></a>
        </div>
      </div>

      <?php endif; ?>
      <?php endwhile; else : _e( 'Sorry, no posts matched your criteria.', 'textdomain' ); endif; ?>

      <?php theme_pagination(); ?>

  	</div>

  	<div class="right-column">

      <?php dynamic_sidebar( 'news_sidebar' ); ?>

  	</div>
  </div>


<?php get_footer(); ?>
