<?php get_header(); ?>

<nav id="homepage-navigation">
	<div class="container">

    <?php wp_nav_menu( array('theme_location'=>'primary','walker' => new Homepage_menu_walker(),'depth' => 0, 'container' => false,'items_wrap' => '%3$s',) ); ?>

	</div>
</nav>

<div class="three-quarters-grid">
	<div class="left-column-home">
		<!-- ARTICLE BLOCK -->
		<div class="block block-coloured">
		  <div class="b-headline gradient-dkblue">
		    <h2>News</h2>
		  </div>
		  <div class="b-container">
				<?php
	        $args = array( 'posts_per_page' => 4 );
	        query_posts($args);

	        $count = 0;

	        if ( have_posts() ) : while ( have_posts() ) : the_post();
	        if(is_sticky()):
	      ?>
					<div class="left-column">
						<?php if ( has_post_thumbnail() ) {
							$thumb_id = get_post_thumbnail_id();
							$thumb_url_array = wp_get_attachment_image_src($thumb_id, 'square', true);
							echo '<img src="'.$thumb_url_array[0].'" />';
						} ?>
						<div class="block block-headline">
								<h2 class="text-havering-blue">
									<a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
								</h2>
								<time datetime="2013-04-06T12:32+00:00">Posted on: <?php echo get_the_date(); ?></time>
								<?php the_excerpt(); ?>
								<a href="<?php the_permalink(); ?>">Read more <i class="fa fa-chevron-circle-right"></i></a>
						</div>
					</div>
				<?php else: ?>
					<div class="right-column">
		        <div class="block block-headline">
		        		<h2 class="text-havering-blue">
		        			<a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
		        		</h2>
								<time datetime="<?php echo get_the_date('Y-d-jTh:i:s'); ?>">Posted on: <?php echo get_the_date(); ?></time>
		        		<?php the_excerpt(); ?>
		        		<a href="<?php the_permalink(); ?>">Read more <i class="fa fa-chevron-circle-right"></i></a>
		        </div>
					</div>
				<?php endif; ?>
					<?php
						$count ++;
				    endwhile;
				    else :
				        _e( 'Sorry, no posts matched your criteria.', 'textdomain' );
				    endif;

					?>
		  </div>
		</div>

		<!-- YAMMER FEED -->
		<div class="block block-coloured">
		  <div class="b-headline gradient-dkblue">
		    <h2>Yammer</h2>
		  </div>
			<script type="text/javascript" src="https://c64.assets-yammer.com/assets/platform_embed.js"></script>
		  <div id="embedded-feed" style="height:735px;max-width:100%;"></div>
			<script type="text/javascript" language="javascript">
			yam.connect.embedFeed(
			{
				container: '#embedded-feed',
				network: 'havering.gov.uk',
				config:
				{
					use_sso: true, // this line enables SSO
					header: false,
					footer: false
				}
			});
			</script>
		</div>
	</div>

	<div class="right-column">

		<?php dynamic_sidebar( 'homepage_sidebar' ); ?>

	</div>
</div>

<?php get_footer(); ?>
