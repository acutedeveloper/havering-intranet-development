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
