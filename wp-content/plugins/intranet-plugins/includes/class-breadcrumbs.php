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
			else
			{
				echo '<li>'.$parent_tax->name.'</li>';
			}
		}
	}
}

function search_cp_url()
{
	$url = array_filter(explode("/", $_SERVER["REQUEST_URI"]));
	foreach ($url as $key => $item)
	{
		if ($item == 'tag'){
			return TRUE;
		}
	}
}

function the_breadcrumb()
{

	$queried_object = get_queried_object();

	$current_post_type_object = get_post_type_object( $queried_object->post_type );
	$current_tax_object = get_object_taxonomies( $queried_object->post_type, 'names' );

	echo '<div class="container">';
	echo '	<ul>';
	echo '<li><a href="'.get_option('home').'">Home</a></li>';

	if(get_query_var('menu'))
	{
		echo '<li><a href="'.get_option('home').'">'.get_query_var('menu').'</a></li>';
	}

	if( get_query_var('post_type') )
	{
		$link = site_url().'/menu/'.$current_post_type_object->rewrite['slug'];
		echo '<li><a href="'.$link.'">'.$current_post_type_object->label.'</a></li>';
	}

	if( get_query_var('name') )
	{
		$post_terms = get_the_terms( get_the_ID(), $current_tax_object[0] );

		if($post_terms)
		{
			foreach($post_terms as $term)
			{
				get_taxonomy_parents($term, $term->taxonomy, $current_post_type_object->rewrite['slug']);
			}
		}

		echo '<li>'.the_title().'</li>';
	}

		// if Single

	echo '	</ul>';
	echo '</div>';

}
