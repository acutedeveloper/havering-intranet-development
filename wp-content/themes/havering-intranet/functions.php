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
) );

$items = wp_get_nav_menu_items( 'homepage-menu' );

//echo "<pre>"; print_r($items); echo "</pre>";

function custom_excerpt_length( $length ) {
	return 20;
}
add_filter( 'excerpt_length', 'custom_excerpt_length', 999 );

add_theme_support( 'post-thumbnails' );


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
  //public $db_fields = array( 'parent' => 'parent_id', 'id' => 'object_id' );
  public $menu_level;

  public function __construct($menu_level = 0)
  {
    $this->$menu_level = $menu_level;
  }

  public function start_lvl( &$output, $depth = 0, $args = array() ) {
	}

  public function end_lvl( &$output, $depth = 0, $args = array() ) {
	}

  // Set the properties of the element which give the ID of the current item and its parent
  public function start_el( &$output, $object, $depth = 0, $args = array(), $current_object_id = 0 )
  {
      if($depth >= 1)
        return;

      $data = 'data-menu-item-id="'.$object->ID.'"';
      $data .= ' data-menu-slug="'.($object->object == 'custom' ? sanitize_title($object->title) : $object->object).'" ';

      if($object->type == 'post_type')
      {
        $link = get_page_link ( $object->object_id );
        $data = "";
      }
      elseif( $object->type == 'taxonomy')
      {
        $link = site_url().'/menu/'.$object->object.'/'.$object->ID.'/'.sanitize_title($object->title);
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
              <?php foreach ($this->cpts as $cpt) { print_r($cpt); ?>
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
}
$custom_nav = new HI_CPT_Nav;
add_action('admin_init', array($custom_nav, 'add_nav_menu_meta_boxes'));

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


//------ DEBUGGING ------//

function inspect_wp_query()
{
  echo '<pre>';
    print_r($GLOBALS['wp_query']);
  echo '</pre>';
}

// If you're looking at other variables you might need to use different hooks
// this can sometimes be a little tricky.
// Take a look at the Action Reference: http://codex.wordpress.org/Plugin_API/Action_Reference
//add_action( 'shutdown', 'inspect_wp_query', 999 ); // Query on public facing pages
//add_action( 'admin_footer', 'inspect_wp_query', 999 ); // Query in admin UI

function printme($array)
{
	echo '<pre>';
		print_r($array);
	echo '</pre>';
}
