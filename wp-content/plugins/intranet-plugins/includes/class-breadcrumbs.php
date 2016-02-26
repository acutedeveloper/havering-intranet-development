<?php

//------ BREAD CRUMBS -------//

function get_taxonomy_parents($parent, $taxonomy, $menu_slug)
{
	$parent_tax = get_term( $parent, $taxonomy );
	$menu_items = wp_get_nav_menu_items ( $menu_slug );

	if($parent_tax->parent != 0)
	{
		get_taxonomy_parents($parent_tax->parent, $parent_tax->taxonomy, $menu_slug);
	}

	foreach($menu_items as $item)
	{
		if($parent_tax->slug == sanitize_title($item->title))
		{
			$link = site_url().'/menu/'.$menu_slug.'/'.$item->ID.'/'.$parent_tax->slug;

			if(0 == $parent_tax->parent)
			{
				echo '<li><a href="'.$link.'">'.$parent_tax->name.'</a></li>';
			}
		}
	}
}

function the_breadcrumb()
{

	echo '<div class="container">';
	echo '	<ul>';
	echo '<li><a href="'.get_option('home').'">Home</a></li>';
	$post_type = get_post_type();

	if( is_search() )
	{
		echo '<li>Search</li>';
		echo '</ul>';
		echo '</div>';
		return;
	}

	if('tribe_events' == $post_type || 'tribe_events' == get_query_var('post_type'))
	{
		echo '<li><a href="'.site_url().'/events">Events</a></li>';
		echo '</ul>';
		echo '</div>';
		return;
	}

	// For the content menu
	if(get_query_var( 'tax_id' ))
	{
		$title = ucfirst(str_replace("-", " ", get_query_var('menu')));
		echo '<li><a href="'.site_url().'/menu/'.get_query_var('menu').'">'.$title.'</a></li>';
	}

	// Add a link to the recent search results
	$url = array_filter(explode("/", $_SERVER["HTTP_REFERER"]));
	if(strpos(end($url),'?s') !== false)
	{
		echo '<li><a href="'.$_SERVER["HTTP_REFERER"].'">Search results</a></li>';
	}

	if( $post_type == 'post' && !is_archive() && !get_query_var( 'menu' ) )
	{
		echo '<li><a href="'.site_url().'/news">News</a></li>';
	}

	if (get_query_var( $post_type.'_tax' ))
	{
		$current_taxonomy = $post_type.'_tax';
		$curr_tax_term = get_query_var( $current_taxonomy );
		$term_object = get_term_by( 'slug', $curr_tax_term, $current_taxonomy, 'object' );
		$current_post_type_object = get_post_type_object( $post_type );

		$link = site_url().'/menu/'.$current_post_type_object->rewrite['slug'];
		echo '<li><a href="'.$link.'">'.$current_post_type_object->label.'</a></li>';

		get_taxonomy_parents($term_object, $current_taxonomy, $current_post_type_object->rewrite['slug']);
	}

	// Custom post types
	$args = array( 'public' => true, '_builtin' => true );

	$my_types = get_post_types( $args, 'names' );

	if ( !in_array( $post_type, $my_types ) )
	{
		$queried_object = get_queried_object();

		//printme($queried_object);
		$current_post_type_object = get_post_type_object( $post_type );

		//printme($current_post_type_object);
		if (!get_query_var( $post_type.'_tax' ))
		{
			$link = site_url().'/menu/'.$current_post_type_object->rewrite['slug'];
			echo '<li><a href="'.$link.'">'.$current_post_type_object->label.'</a></li>';
		}


		if( get_query_var('name') )
		{
			$current_tax_object = get_object_taxonomies( $post_type, 'names' );

			$post_terms = get_the_terms( get_the_ID(), $current_tax_object[0] );

			if($post_terms)
			{
				foreach($post_terms as $term)
				{
					get_taxonomy_parents($term, $term->taxonomy, $current_post_type_object->rewrite['slug']);
				}
			}

		}
	}

	echo '	</ul>';
	echo '</div>';

}
