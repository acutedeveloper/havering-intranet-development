<?php get_header(); ?>

  <div class="three-quarters-grid">
  	<div class="left-column-home">

      <article>
          <h1><?php _e( '404 - Article Not Found' ); ?></h1>


      </article>

    </div>

    <div class="right-column">

      <?php dynamic_sidebar( 'news_sidebar' ); ?>

    </div>
  </div>

<?php get_footer(); ?>
