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

function the_breadcrumb()
{

	echo '<div class="container">';
	echo '	<ul>';
	echo '<li><a href="'.get_option('home').'">Home</a></li>';
	$post_type = get_post_type();

	if( is_search() )
	{
		echo 'search';
	}

	if( $post_type == 'page')
	{
		echo '<li>'.the_title().'</li>';
	}

	if( $post_type == 'post' && !is_archive() )
	{
		echo '<li><a href="'.site_url().'/news">News</a></li>';
		echo '<li>'.the_title().'</li>';
	}

	if( 'page' != $post_type && 'post' != $post_type )
	{
		$queried_object = get_queried_object();

		//printme($queried_object);
		$current_post_type_object = get_post_type_object( $post_type );

		//printme($current_post_type_object);

		$link = site_url().'/menu/'.$current_post_type_object->rewrite['slug'];
		echo '<li><a href="'.$link.'">'.$current_post_type_object->label.'</a></li>';

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
		echo '<li>'.the_title().'</li>';

	}

	if(is_archive())
	{
		//echo '<li><a href="'.site_url().'/news">News</a></li>';
		echo '<li>'.the_archive_title().'</li>';
	}

		// if Single

	echo '	</ul>';
	echo '</div>';

}
