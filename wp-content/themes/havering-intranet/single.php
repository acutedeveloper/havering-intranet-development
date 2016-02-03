<?php get_header(); ?>

  <div class="three-quarters-grid">
  	<div class="left-column-home">

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

      <?php dynamic_sidebar( 'content_sidebar' ); ?>


    </div>
  </div>

<?php get_footer(); ?>
