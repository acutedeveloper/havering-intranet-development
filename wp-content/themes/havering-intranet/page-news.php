<?php
/**
* Template Name: news
*/
 get_header(); ?>

  <div class="three-quarters-grid">
  	<div class="left-column">

      <?php
        $args = array( 'posts_per_page' => 10 );
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

  		<ol class="pagination">
  			<li><a class="active" href="#">Previous</a></li>
  			<li><a href="#">1</a></li>
  			<li><a class="active" href="#">2</a></li>
  			<li><a href="#">3</a></li>
  			<li><a href="#">4</a></li>
  			<li><a href="#">5</a></li>
  			<li><a href="#">6</a></li>
  			<li><a href="#">7</a></li>
  			<li><a class="active" href="#">Next</a></li>
  		</ol>

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
