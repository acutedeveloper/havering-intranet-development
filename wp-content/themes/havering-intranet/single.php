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

      <!-- Useful Links -->
      <div class="block block-coloured">
        <div class="b-headline gradient-pink">
          <h2>Useful links</h2>
        </div>

        <?php wp_nav_menu( array('theme_location'=>'useful-links', 'container_class' => 'b-container', 'menu_class' => 'sidebar-links') ); ?>

      </div>

      <!-- Popular Links -->
      <div class="block block-coloured">
        <div class="b-headline gradient-lime">
          <h2>Popular links (FTBC)</h2>
          <!-- http://www.wpbeginner.com/wp-tutorials/how-to-track-popular-posts-by-views-in-wordpress-without-a-plugin/ -->
        </div>

        <div class="b-container">
          <ul class="sidebar-links">
            <li><a href="#">Corporate plans</a></li>
            <li><a href="#">Corporate strategies</a></li>
            <li><a href="#">CMT Structure</a></li>
            <li><a href="#">Agency workers</a></li>
            <li><a href="#">Committees</a></li>
            <li><a href="#">Complaints</a></li>
          </ul>
        </div>
      </div>

    </div>
  </div>

<?php get_footer(); ?>
