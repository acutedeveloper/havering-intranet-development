<?php
/*
Template Name: Archives
*/

// This page needs to show initially:
// A list of custom posttypes
// The selected posttype
// The postype menu level 1

// Everything is being feed by the wp_nav_menu

get_header(); ?>

		<?php

      $current_category = get_queried_object();

      // printme($current_category->query_var);

    ?>

    <div id="breadcrumbs">
    	<div class="container">
    		<ul>
    			<li><a href="#">Grandparent</a></li>
    			<li><a href="#">Parent</a></li>
    			<li><a href="#">Child</a></li>
    			<li><a href="#">Grandchild</a></li>
    			<li>Great-Grandchild</li>
    		</ul>
    	</div>
    </div>

    <div class="one-up-grid">
    	<div id="content-navigation">
					<h1>Here is the tax page</h1>
    	</div>
    </div>
<?php get_footer(); ?>
