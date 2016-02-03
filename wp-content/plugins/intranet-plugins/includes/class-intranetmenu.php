<?php

if(!class_exists('intranetMenu'))
{

class intranetMenu
{

  /**
	 *
	 *  Intranet Menu
	 *
	 *  This class will allow a user to navigate the main content.
	 *
	 */

	public function __construct()
	{
		add_action( 'init', array( $this, 'add_rewrites' ) );
		register_activation_hook( __FILE__, array( $this, 'rewrite_activation' ));
		add_filter( 'query_vars', array( $this, 'rewrite_add_var' ));
		add_action( 'template_redirect', array( $this, 'catch_form' ));
	}

	public static function add_rewrites()
	{

		add_rewrite_rule(
			'^menu/?([a-z\-]*)/?$',
			'index.php?menu=$matches[1]&post_type=$matches[1]',
			'top'
		);

		add_rewrite_rule(
			'^menu/?([a-z\-]*)/([0-9]*)/([a-z\-]*)/?$',
			'index.php?menu=$matches[1]&tax_id=$matches[2]&tax_slug=$matches[3]',
			'top'
		);

	}

	// Only need to have this done once
	public static function rewrite_activation()
	{
		$this->add_rewrites();
		flush_rewrite_rules();
	}

	public function rewrite_add_var( $vars )
	{
		$vars[] = 'menu';
		$vars[] = 'post_type';
		$vars[] = 'tax_id';
		$vars[] = 'tax_slug';
	  return $vars;
	}

	public static function catch_form()
	{
		if( get_query_var('menu') && get_query_var('tax_id') && get_query_var('tax_slug') )
		{
      include(CPT_PLUGIN_DIR . '/views/' . 'intranet-menu.php');
			exit();
		}
		elseif( get_query_var('menu') )
		{
      include(CPT_PLUGIN_DIR . '/views/' . 'intranet-menu.php');
			exit();
		}

	}

  public function get_second_level_links()
  {
		$result = array(
			'message' => 'message',
			'type' => 'success'
			);

		echo json_encode($result);
		die();
  }

}

$intranetMenu = new intranetMenu;

add_action( 'wp_ajax_get_menu_level', 'get_menu_level' );
add_action( 'wp_ajax_nopriv_get_menu_level', 'get_menu_level' );

function get_menu_level()
{
	// Do we need to check the wp_nonce??
	require_once CPT_PLUGIN_DIR . 'assets/php/gump/gump.class.php';

	// Let clean the data
	$gump = new GUMP();
	$sanitized_data = $gump->sanitize($_GET);

	// Get the post_type
	$menu_slug = $sanitized_data['menu'];
	$menu_item_id = $sanitized_data['menu_item_id'];
	$menu_level = $sanitized_data['menu_level'];

	// depending on the value
	if($menu_level == 'level_two')
	{
		wp_nav_menu( array('theme_location'=> $menu_slug, 'depth' => 1, 'walker' => new Content_menu_walker(2), 'container' => false ) );
	}
	elseif ($menu_level == 'level_three')
	{
		wp_nav_menu( array('theme_location'=> $menu_slug, 'depth' => 1, 'level' => 2, 'child_of' => (int)$menu_item_id, 'walker' => new Content_menu_walker(3), 'container' => false) );
	}
	die();
}


}
