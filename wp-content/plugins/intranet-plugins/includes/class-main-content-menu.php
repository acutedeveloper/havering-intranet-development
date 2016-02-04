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

Class Homepage_menu_walker extends Walker
{
  public $db_fields = array( 'parent' => 'parent_id', 'id' => 'object_id' );
  public $homepage_menu_count;
  public static $element_position = 1;

  function __construct()
  {
    $this->homepage_menu_count = wp_get_nav_menu_object( 'homepage-menu' );
  }

  public function start_lvl( &$output, $depth = 0, $args = array() )
  {
    $output .= "&nbsp;";
  }

  public function end_lvl( &$output, $depth = 0, $args = array() )
  {
    $output .= "&nbsp;";
  }

  // Set the properties of the element which give the ID of the current item and its parent
  public function start_el( &$output, $object, $depth = 0, $args = array(), $current_object_id = 0 )
  {
      //echo "<pre>"; print_r($object); echo "</pre>";
      $output .= (self::$element_position % 4 == 1) ? "<div class=\"four-up-grid\">\n": "" ;
      $output .= "<div class=\"grid-item\">\n";
      $link = ( $object->object == 'page') ? get_page_link ( $object->object_id ) : site_url().'/menu/'.$object->post_name;
      $output .= "  <h3><a href=".$link.">".$object->title."</a></h3>\n";
      $output .= ( $object->post_content == "" ) ? "" : "  <p>".$object->post_content."</p>\n";
  }

  public function end_el( &$output, $object, $depth = 0, $args = array() )
  {
    $output .= "</div>\n";
    $output .= (self::$element_position % 4 == 0) ? "</div>\n": "" ;
    self::$element_position++;
  }
}

Class Content_menu_walker extends Walker_Nav_Menu
{
  public $menu_level;

  function __construct($menu_level = 0)
  {
    $this->menu_level = $menu_level;
  }

  public function start_lvl( &$output, $depth = 0, $args = array() ) {
	}

  public function end_lvl( &$output, $depth = 0, $args = array() ) {
	}

  public function start_el( &$output, $object, $depth = 0, $args = array(), $current_object_id = 0 )
  {
      if($depth >= 1)
        return;

      //printme($object);
      $data = 'data-menu-item-id="'.$object->ID.'"';
      $data .= ' data-menu-slug="'.($object->object == 'custom' ? sanitize_title($object->title) : get_query_var('menu')).'" ';

      if($object->type == 'post_type')
      {
        $link = get_permalink($object->object_id);
        $data = "";
      }
      elseif( $object->type == 'taxonomy')
      {
        if($this->menu_level == 3)
        {
          $link = get_term_link( sanitize_title($object->title), $object->object );
        }
        else
        {
          $link = site_url().'/menu/'.get_query_var('menu').'/'.$object->ID.'/'.sanitize_title($object->title);
        }
      }
      else
      {
        $link = site_url().'/menu/'.$object->post_name;
      }
      $output .= "<li>\n";
      $output .= "  <a href=".$link." ".$data." >".$object->title."</a>\n";
      $output .= ( trim($object->post_content) == NULL ) ? NULL : "  <p>".$object->post_content."</p>\n";
  }

  public function end_el( &$output, $object, $depth = 0, $args = array() )
  {
    if($depth >= 1)
      return;
    $output .= "</li>\n";
  }

}

Class Taxonomy_menu_walker extends Walker_Nav_Menu
{
  public $menu_level;

  function __construct($menu_level = 0)
  {
    $this->menu_level = $menu_level;
  }

  public function start_lvl( &$output, $depth = 0, $args = array() ) {
	}

  public function end_lvl( &$output, $depth = 0, $args = array() ) {
	}

  public function start_el( &$output, $object, $depth = 0, $args = array(), $current_object_id = 0 )
  {
      if($depth >= 1)
        return;

  }

  public function end_el( &$output, $object, $depth = 0, $args = array() )
  {
    if($depth >= 1)
      return;
  }

}

if ( !class_exists('HI_CPT_Nav')) {
    class HI_CPT_Nav {

      protected $cpts;

      public function __construct(){
        $this->get_cpts();
      }

        public function add_nav_menu_meta_boxes() {
        	add_meta_box(
        		'wl_login_nav_link',
        		__('Custom Posttype Links'),
        		array( $this, 'nav_menu_link'),
        		'nav-menus',
        		'side',
        		'low'
        	);
        }

        public function get_cpts() {
      		$cpts = array();
      		$has_archive_cps = get_post_types(
      			array(
      				'has_archive'	=> true,
      				'_builtin' => false
      			),
      			'object'
      		);
      		foreach ( $has_archive_cps as $ptid => $pt ) {
      			$to_show = $pt->show_in_nav_menus && $pt->publicly_queryable;
      			if ( apply_filters( "show_{$ptid}_archive_in_nav_menus", $to_show, $pt ) ) {
      				$cpts[] = $pt;
      			}
      		}
      		if ( ! empty( $cpts ) ) {
      			$this->cpts = $cpts;
      		}
      	}

        public function nav_menu_link() {
            $this->get_cpts();
          ?>
        	<div id="posttype-wl-login" class="posttypediv">
        		<div id="tabs-panel-wishlist-login" class="tabs-panel tabs-panel-active">
        			<ul id ="wishlist-login-checklist" class="categorychecklist form-no-clear">
              <?php foreach ($this->cpts as $cpt) { ?>
        				<li>
        					<label class="menu-item-title">
        						<input type="checkbox" class="menu-item-checkbox" name="menu-item[-1][menu-item-object-id]" value="-1"> <?php echo $cpt->label; ?>
        					</label>
        					<input type="hidden" class="menu-item-type" name="menu-item[-1][menu-item-type]" value="custom">
        					<input type="hidden" class="menu-item-title" name="menu-item[-1][menu-item-title]" value="<?php echo $cpt->label ?>">
        					<input type="hidden" class="menu-item-url" name="menu-item[-1][menu-item-url]" value="<?php echo get_post_type_archive_link( $cpt->name ); ?>">
        				</li>
                <?php } ?>
        			</ul>
        		</div>
        		<p class="button-controls">
        			<span class="list-controls">
        				<a href="/wordpress/wp-admin/nav-menus.php?page-tab=all&amp;selectall=1#posttype-page" class="select-all">Select All</a>
        			</span>
        			<span class="add-to-menu">
        				<input type="submit" class="button-secondary submit-add-to-menu right" value="Add to Menu" name="add-post-type-menu-item" id="submit-posttype-wl-login">
        				<span class="spinner"></span>
        			</span>
        		</p>
        	</div>
        <?php }
    }

		$custom_nav = new HI_CPT_Nav;
		add_action('admin_init', array($custom_nav, 'add_nav_menu_meta_boxes'));

}
