<?php
/*
 Template Name: Intranet Menu page
 *
*/
?>

<?php get_header(); ?>

<div id="breadcrumbs">
  <?php the_breadcrumb(); ?>
</div>

<div class="one-up-grid">
  <div id="content-navigation">
    <?php
        $taxonomy = 'hi_'.str_replace("-","_", get_query_var('menu')).'_tax';

        // Because cpts cannot be more than 20 characters we need to filter for
        // those custom post types that have truncated names
        if(get_query_var('menu') == "health-and-safety")
        {
          $cpt = new stdClass();
          $cpt->label = 'Health and Safety';
          $taxonomy = 'hi_health_safety_tax';
        }
        else if(get_query_var('menu') == "committee-services")
        {
          $cpt = new stdClass();
          $cpt->label = 'Committee Services';
          $taxonomy = 'hi_committee_service_tax';
        }
        else
        {
          $cpt = get_post_type_object( 'hi_'.str_replace("-","_", get_query_var('menu')) );
        }

        $display_style = get_query_var('tax_id') ? 'display-none' : '';

        wp_nav_menu( array('theme_location'=>'primary', 'depth' => 1, 'walker' => new Content_menu_walker(1, get_query_var('menu')), 'container_class' => 'links-top-level' ) );
        wp_nav_menu( array('theme_location' => get_query_var('menu'), 'walker' => new Content_menu_walker(2, get_query_var('menu')), 'items_wrap' => '<h3>'.$cpt->label.'</h3><ul>%3$s</ul>', 'container_class' => 'links-second-level display-desktop-300 '.$display_style) );

        if(get_query_var('tax_id'))
        {
          $term = get_term_by( 'slug', get_query_var('tax_slug'), $taxonomy );
          wp_nav_menu( array('theme_location' => get_query_var('menu'), 'depth' => 1, 'level' => 2, 'child_of' => (int)get_query_var('tax_id'), 'walker' => new Content_menu_walker(3, get_query_var('menu')), 'items_wrap' => '<h3>'.$term->name.'</h3><ul>%3$s</ul>', 'container_class' => 'links-third-level display-desktop-600 display') );
        }
        else {
          echo '<div class="links-third-level"></div>';
        }

    ?>
  </div>
</div>

<?php get_footer(); ?>
