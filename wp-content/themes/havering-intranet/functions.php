<?php

register_nav_menus( array(
  'primary' => 'Primary',
  'useful-links' => 'Useful links',
  'help-with-work' => 'Help with work',
  'support-for-you' => 'Support for you',
  'human-resources' => 'HR',
  'self-service' => 'Self-service',
  'ict' => 'ICT',
  'committee-services' => 'Committee services',
  'health-and-safety' => 'Health and Safety',
  'our-services' => 'Our Services',
  'about-havering' => 'About Havering',
  'my-test' => 'My Test'
  )
);

function intranet_sidebars() {

  register_sidebar( array(
  	'name'          => 'Homepage Sidebar',
  	'id'            => 'homepage_sidebar',
  	'description'   => '',
    'class'         => '',
  	'before_widget' => '<div class="block block-coloured">',
  	'after_widget'  => '</div>',
  	'before_title'  => '<div class="b-headline gradient-dkblue"><h2>',
  	'after_title'   => '</h2></div>'
    )
  );

  register_sidebar( array(
  	'name'          => 'News Sidebar',
  	'id'            => 'news_sidebar',
  	'description'   => '',
    'class'         => '',
    'before_widget' => '<div class="block block-coloured">',
  	'after_widget'  => '</div>',
  	'before_title'  => '<div class="b-headline gradient-dkblue"><h2>',
  	'after_title'   => '</h2></div>'
    )
  );

  register_sidebar( array(
  	'name'          => 'Content Pages Sidebar',
  	'id'            => 'content_sidebar',
  	'description'   => '',
    'class'         => '',
    'before_widget' => '<div class="block block-coloured">',
  	'after_widget'  => '</div>',
  	'before_title'  => '<div class="b-headline gradient-dkblue"><h2>',
  	'after_title'   => '</h2></div>'
    )
  );

}

add_action( 'widgets_init', 'intranet_sidebars' );

function custom_excerpt_length( $length ) {
	return 20;
}

add_filter( 'excerpt_length', 'custom_excerpt_length', 999 );

add_theme_support( 'post-thumbnails' );


//------ LOAD JAVASCRIPT ------//


if (!is_admin()) add_action("wp_enqueue_scripts", "my_jquery_enqueue", 11);

function my_jquery_enqueue() {
   wp_deregister_script('jquery');
   wp_register_script('jquery', get_bloginfo('stylesheet_directory')."/library/js/jquery-2.0.0b2.js", false, null);
   wp_register_script('jquery-ui', get_bloginfo('stylesheet_directory')."/library/js/jquery-ui.js", false, null);
   wp_register_script('bxslider', get_bloginfo('stylesheet_directory')."/library/js/bxslider/jquery.bxslider.js", false, null);
   wp_enqueue_script('jquery');
   wp_enqueue_script('jquery-ui');
   wp_enqueue_script('bxslider');
}

//------ ADD IMAGE SIZES ------//

if ( function_exists( 'add_image_size' ) ) {
    add_image_size( 'landscape-4x3', 600, 450 );
    add_image_size( 'landscape-16x9', 1280, 720 );
    add_image_size( 'square', 600, 600, array( 'left', 'top' ) );
}

add_filter('image_size_names_choose', 'my_image_sizes');
function my_image_sizes($sizes) {
    $addsizes = array(
        "landscape-4x3" => __( "Landscape 4x3" ),
        "landscape-16x9" => __( "Landscape 16x9" ),
        "square" => __( "Square" ),
    );
    $newsizes = array_merge($sizes, $addsizes);
    return $newsizes;
}

//------ REDIRECT TEMPLATE ------//

add_filter( 'template_include', function( $template )
{
    // your custom post types
    $args = array(
    'public'   => true,
    '_builtin' => false
    );

    $my_types = get_post_types( $args, 'names' );
    $post_type = get_post_type();

    if (is_tax())
      return $template;

    if ( in_array( $post_type, $my_types ) )
        return get_stylesheet_directory() . '/page.php';

    return $template;
});

//------ PAGINATION ------//

function theme_pagination($pages = '', $range = 2)
{
    $showitems = ($range * 2)+1;

    $paged = ( get_query_var( 'paged' ) ) ? get_query_var( 'paged' ) : 1;

    if($pages == '')
    {
     global $wp_query;
     $pages = $wp_query->max_num_pages;

     if(!$pages)
     {
         $pages = 1;
     }
    }

    if(1 != $pages)
    {
     echo "<ol class='pagination'>";
     if($paged > 2 && $paged > $range+1 && $showitems < $pages) echo "<li><a href='".get_pagenum_link(1)."'>&laquo;</a><li>";
     if($paged > 1 && $showitems < $pages) echo "<li><a href='".get_pagenum_link($paged - 1)."'>&lsaquo;</a><li>";

     for ($i=1; $i <= $pages; $i++)
     {
         if (1 != $pages &&( !($i >= $paged+$range+1 || $i <= $paged-$range-1) || $pages <= $showitems ))
         {
             echo ($paged == $i)? "<li><a class='active' href='#'>".$i."</a></li>":"<li><a href='".get_pagenum_link($i)."'>".$i."</a><li>";
         }
     }

     if ($paged < $pages && $showitems < $pages) echo "<li><a href='".get_pagenum_link($paged + 1)."'>&rsaquo;</a><li>";
     if ($paged < $pages-1 &&  $paged+$range-1 < $pages && $showitems < $pages) echo "<li><a href='".get_pagenum_link($pages)."'>&raquo;</a><li>";
     echo "</ol>\n";
    }
}
