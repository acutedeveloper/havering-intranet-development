<?php
/*
Template Name: Archives
*/

// This page needs to show initially:
// A list of custom posttypes
// The selected posttype
// The postype menu level 1

// Everything is being feed by the wp_nav_menu

	get_header();

  ?>

		<div class="three-quarters-grid">
	  	<div class="left-column">

				<?php
				the_archive_title( '<h1 class="page-title">', '</h1>' );
				?>

				<hr/>

				<?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>

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

				<?php endwhile; else : _e( 'Sorry, no posts matched your criteria.', 'textdomain' ); endif; ?>
				<?php paginate_links (); ?>
    	</div>

			<div class="right-column">

	      <?php dynamic_sidebar( 'news_sidebar' ); ?>

	  	</div>
    </div>
<?php get_footer(); ?>
