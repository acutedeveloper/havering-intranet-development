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
						<?php if ( has_post_thumbnail() ) { the_post_thumbnail(); } ?>
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
		        <div class="block block-headline">
		        		<h2 class="text-havering-blue">
		        			<a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
		        		</h2>
								<time datetime="<?php echo get_the_date('Y-d-jTh:i:s'); ?>">Posted on: <?php echo get_the_date(); ?></time>
		        		<?php the_excerpt(); ?>
		        		<a href="<?php the_permalink(); ?>">Read more <i class="fa fa-chevron-circle-right"></i></a>
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
					header: false,
					footer: false
				}
			});
			</script>
		</div>
	</div>

	<div class="right-column">

		<!-- Calendar -->
		<div class="block block-coloured">
			<div class="b-headline gradient-dkblue">
				<h2>Meeting &amp; Events</h2>
			</div>
			<div class="b-container">
				<h3>January 2016</h3>

				<div class="block block-calendar">
					<div class="block-date gradient-pink">
						<div class="day">Mon</div>
						<div class="date">16</div>
					</div>
					<h2 class="text-pink"><a href="#">Headline Lorem ipsum dolor</a></h2>
					<p>Sed tellus metus, fringilla vel.</p>
					<a href="#">Read more <i class="fa fa-chevron-circle-right"></i></a>
				</div>

				<div class="block block-calendar">
					<div class="block-date gradient-pink">
						<div class="day">Mon</div>
						<div class="date">16</div>
					</div>
					<h2 class="text-pink"><a href="#">Headline Lorem ipsum dolor</a></h2>
					<p>Sed tellus metus, fringilla vel.</p>
					<a href="#">Read more <i class="fa fa-chevron-circle-right"></i></a>
				</div>

				<div class="block block-calendar">
					<div class="block-date gradient-pink">
						<div class="day">Mon</div>
						<div class="date">16</div>
					</div>
					<h2 class="text-pink"><a href="#">Headline Lorem ipsum dolor</a></h2>
					<p>Sed tellus metus, fringilla vel.</p>
					<a href="#">Read more <i class="fa fa-chevron-circle-right"></i></a>
				</div>

			</div>
		</div>

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
