<?php
/*
Plugin Name: Havering Intranet Functions
Plugin URI: http://www.acumendesign.co.uk
Description: A collections of features and function exclusive for the Havering Intranet Website
Version: 0.1
Author: @acute_designer
Author Nigel M Peters
License: GPL2
*/

if ( ! defined( 'ABSPATH' ) ) exit;

define("CPT_VERSION_NUMBER", "0.1");
define("CPT_PLUGIN_DIR", plugin_dir_path(__FILE__));
define("CPT_PLUGIN_URL", plugin_dir_url( __FILE__ ));

//Load up the classes for wordpress
require_once CPT_PLUGIN_DIR . 'includes/debugging-tools.php';
require_once CPT_PLUGIN_DIR . 'includes/class-custom-posttypes.php';
require_once CPT_PLUGIN_DIR . 'includes/class-breadcrumbs.php';
require_once CPT_PLUGIN_DIR . 'includes/class-main-content-menu.php';

add_action( 'wp_enqueue_scripts', 'enqueue_and_register_my_scripts' );

function enqueue_and_register_my_scripts()
{
  // Register javascript
  wp_register_script('intranet_script', plugins_url('assets/js/intranet-script.js', __FILE__), array('jquery'),'1.1', true);

  // Localize the script with new data
  // To make it easy for JS to access wordpress URLs ^_^
  $site_parameters = array(
      'site_url' => get_site_url(),
      'ajax_url' => admin_url( 'admin-ajax.php' ),
      'theme_directory' => get_template_directory_uri()
      );

  wp_localize_script( 'intranet_script', 'wp_js_object', $site_parameters );

  // Enqueued script with localized data.
  wp_enqueue_script( 'intranet_script' );
}
