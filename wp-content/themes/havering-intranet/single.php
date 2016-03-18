<?php get_header(); ?>

  <div class="three-quarters-grid">
  	<div class="left-column-home">

      <article>
        <?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>
        	<h1><?php the_title(); ?></h1>

          <time datetime="<?php echo get_the_date('Y-d-jTh:i:s'); ?>">Posted on: <?php echo get_the_date(); ?></time>
          <?php the_content(); ?>


        <?php if( have_rows('file_resources') ): ?>
    		<h2>File downloads</h2>
    		<table>
    		<?php
    			while ( have_rows('file_resources') ) : the_row();
    				$file = get_sub_field('file_resource_download');
    		?>

    			<tr>
            <td><i class="fa <?php echo get_mime_type_icon($file['mime_type']) ?>"></i></td>
    				<td><a href="<?php echo $file['url']; ?>"><?php echo $file['title']; ?></a></td>
    				<td><?php echo $file['description']; ?></td>
    			</tr>

    		<?php endwhile;?>
    		</table>
    		<?php else: ?>

    		<?php endif; ?>

        <div class="byline">
        <?php echo '<p>This story was published by: '.get_the_author_meta('user_firstname').' '.get_the_author_meta('user_lastname').' – <a href="mailto:'.get_the_author_meta('email').'">'.get_the_author_meta('email').'</a>'; ?>
        <?php echo '<br>Last updated: '.get_the_modified_date(  ).'</p>'; ?>
        </div>

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
