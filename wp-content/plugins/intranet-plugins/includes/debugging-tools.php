<?php

//------ DEBUGGING ------//

if(WP_DEBUG == true){

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

  add_action( 'shutdown', 'x_shutdown' );
  function x_shutdown() {
    if ( ! is_admin() ) {
      global $wp;
      echo '<pre><code>';
      echo '$wp->query_vars: ';
      print_r( $wp->query_vars );
      echo '</code></pre>';
    }
  }

}

function printme($array)
{
	echo '<pre>';
		print_r($array);
	echo '</pre>';
}
