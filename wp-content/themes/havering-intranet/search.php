<?php
/**
* Template Name: search
*/
 get_header();

 global $query_string;

 $query_args = explode("&", $query_string);
 $search_query = array();

 if( strlen($query_string) > 0 ) {
 	foreach($query_args as $key => $string) {
 		$query_split = explode("=", $string);
 		$search_query[$query_split[0]] = urldecode($query_split[1]);
 	} // foreach
 } //if

 $search = new WP_Query($search_query);

 global $wp_query;
 $total_results = $wp_query->found_posts;

 ?>

<div class="three-quarters-grid">
	<div class="left-column">
		<h1 class="archive-title"><span><?php _e( $total_results.' Search Results for:', 'textdomain' ); ?></span> <?php echo esc_attr(get_search_query()); ?></h1>

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
					<?php if(get_post_type() == 'post'): ?>
					<time datetime="<?php echo get_the_date('Y-d-jTh:i:s'); ?>">Posted on: <?php echo get_the_date(); ?></time>
					<?php endif; ?>
					<?php the_excerpt(); ?>
					<a href="<?php the_permalink(); ?>">Read more <i class="fa fa-chevron-circle-right"></i></a>
				</div>
			</div>

		<?php endwhile; else : ?>
      <h1><?php _e( 'Sorry, no posts matched your criteria.', 'textdomain' ); ?></h1>
    <?php endif; ?>
    <?php theme_pagination(); ?>
	</div>

	<div class="right-column">

		<?php dynamic_sidebar( 'news_sidebar' ); ?>

	</div>
</div>

<?php get_footer(); ?>
