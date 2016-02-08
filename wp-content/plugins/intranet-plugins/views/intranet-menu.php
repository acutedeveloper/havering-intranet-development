<?php
/*
 Template Name: Intranet Menu page
 *
*/
?>

<?php get_header(); ?>

<div class="one-up-grid">
  <div id="content-navigation">
    <?php

        wp_nav_menu( array('theme_location'=>'primary', 'depth' => 1, 'walker' => new Content_menu_walker(1, get_query_var('menu')), 'container_class' => 'links-top-level' ) );
        wp_nav_menu( array('theme_location' => get_query_var('menu'), 'walker' => new Content_menu_walker(2, get_query_var('menu')), 'container_class' => 'links-second-level display-desktop-300') );

        if(get_query_var('tax_id'))
        {
          wp_nav_menu( array('theme_location' => get_query_var('menu'), 'depth' => 1, 'level' => 2, 'child_of' => (int)get_query_var('tax_id'), 'walker' => new Content_menu_walker(3, get_query_var('menu')), 'container_class' => 'links-third-level display-desktop-600') );
        }
        else {
          echo '<div class="links-third-level"></div>';
        }

    ?>
  </div>
</div>

<?php get_footer(); ?>
