<?php get_header(); ?>

  <div class="three-quarters-grid">
  	<div class="left-column-home">

      <?php

        $term_object = new stdClass();
        $show_menu = FALSE;
        $post_type = get_post_type();

        if(get_query_var( $post_type.'_tax' ))
        {
          $current_taxonomy = $post_type.'_tax';
          $curr_tax_term = get_query_var( $current_taxonomy );
          $page_object = get_queried_object();
          //printme($page_object->post_name);

          $term_object = get_term_by( 'slug', $curr_tax_term, $current_taxonomy, 'object' );

          $show_menu = $term_object->parent != 0 ? TRUE : FALSE;
        }
        elseif(get_query_var('name'))
        {
          $page_object = get_queried_object();
          // printme($page_object->post_name);

          $current_taxonomy = $page_object->post_type.'_tax';

          $current_tax_object = get_object_taxonomies( $post_type, 'names' );
      		$post_terms = get_the_terms( get_the_ID(), $current_tax_object[0] );
          $term_object = $post_terms[0];

          if(is_object($term_object))
            $show_menu = $term_object->parent != 0 ? TRUE : FALSE;

        }

        if($show_menu == TRUE)
        {

          $args = array(
            'posts_per_page' => 80, 'orderby' => 'date', 'order' => 'asc',
          	'tax_query' => array(
          		array(
          			'taxonomy' => $current_taxonomy,
          			'field' => 'slug',
          			'terms' => $term_object->slug
          		)
          	)
          );

  			  $hi_query = new WP_Query( $args );

          // Count the total Posts
    			$total_posts = count($hi_query->posts);

    			$count = 0;

    			global $wp_query;

    			// Put default query object in a temp variable
    			$tmp_query = $wp_query;
    			// Now wipe it out completely
    			$wp_query = null;
    			// Re-populate the global with our custom query
    			$wp_query = $hi_query;

        ?>
      <!-- THE MENU -->
      <div id="subpage-nav" class="two-up-grid">

        <?php if ( $hi_query->have_posts() ) : while ( $hi_query->have_posts() ) : $hi_query->the_post(); ?>

          <?php if($count == 0): ?><div class="grid-item"><ul><?php endif; ?>

          <li><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></li>

          <?php if($count == round($total_posts/2)-1): ?></div><div class="grid-item"><ul><?php endif; ?>

          <?php if($count == $total_posts-1): ?></ul></div><?php endif; ?>

        <?php $count++; endwhile; endif; ?>
        <?php rewind_posts(); ?>
      </div>

      <?php
          // Re-populate the global query to its original setting
          $wp_query = $tmp_query;
        }

        // We want to get the frst post that is related to this tax term
        if(get_query_var( $post_type.'_tax' ))
        {
          $args['posts_per_page'] = 1;
          query_posts( $args );
        }

       ?>
      <article>
        <?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>
        	<h1><?php the_title(); ?></h1>

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

        <?php echo '<p>This page is maintained by: '.get_the_author_meta('user_firstname').' '.get_the_author_meta('user_lastname').' – <a href="mailto:'.get_the_author_meta('email').'">'.get_the_author_meta('email').'</a>'; ?>
        <?php echo '<br>Last updated: '.get_the_modified_date(  ).'</p>'; ?>

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
