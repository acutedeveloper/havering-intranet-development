<?php

// Reference for using this in conjunction with https://wordpress.org/plugins/members/
// http://justintadlock.com/archives/2010/07/10/meta-capabilities-for-custom-post-types
// This allows specific roles to only access a specific CPT

// Array to pass to class

$hi_cpts = array(
	array(
		'name' => 'Help with work',
		'cpt_name' => 'hi_help_with_work',
		'cpt_shortname' => 'hi_hww',
		'cpt_slug' => 'help-with-work',
		'cpt_taxonomy' => 'hi_help_with_work_tax'
	),
	array(
		'name' => 'Support for you',
		'cpt_name' => 'hi_support_for_you',
		'cpt_shortname' => 'hi_sfy',
		'cpt_slug' => 'support-for-you',
		'cpt_taxonomy' => 'hi_support_for_you_tax'
	),
	array(
		'name' => 'Human Resources',
		'cpt_name' => 'hi_human_resources',
		'cpt_shortname' => 'hi_humanre',
		'cpt_slug' => 'human-resources',
		'cpt_taxonomy' => 'hi_human_resources_tax'
	),
	array(
		'name' => 'Self Service',
		'cpt_name' => 'hi_self_service',
		'cpt_shortname' => 'hi_selser',
		'cpt_slug' => 'self-service',
		'cpt_taxonomy' => 'hi_self_service_tax'
	),
	array(
		'name' => 'ICT',
		'cpt_name' => 'hi_ict',
		'cpt_shortname' => 'hi_ict',
		'cpt_slug' => 'ict',
		'cpt_taxonomy' => 'hi_ict_tax'
	),
	array(
		'name' => 'Committee Services',
		'cpt_name' => 'hi_committee_service',
		'cpt_shortname' => 'hi_comser',
		'cpt_slug' => 'committee-services',
		'cpt_taxonomy' => 'hi_committee_serv_tax'
	),
	array(
		'name' => 'Health and Safety',
		'cpt_name' => 'hi_health_safety',
		'cpt_shortname' => 'hi_has',
		'cpt_slug' => 'health-and-safety',
		'cpt_taxonomy' => 'hi_health_safety_tax'
	),
	array(
		'name' => 'Our Services',
		'cpt_name' => 'hi_our_services',
		'cpt_shortname' => 'hi_ourser',
		'cpt_slug' => 'our-services',
		'cpt_taxonomy' => 'hi_our_services_tax'
	),
	array(
		'name' => 'About Havering',
		'cpt_name' => 'hi_about_havering',
		'cpt_shortname' => 'hi_abhav',
		'cpt_slug' => 'about-havering',
		'cpt_taxonomy' => 'hi_about_havering_tax'
	),
);

// Init class
$cpts = new Build_intranet_cpts ($hi_cpts);

// Class
class Build_intranet_cpts {

		public $cpts_array;

    function __construct($array) {
        $this->cpts_array = $array;
				add_action( 'init', array(&$this, 'add_intranet_post_types'), 0);
				add_action( 'init', array(&$this, 'add_intranet_taxonomies') );
				add_action( 'init', array(&$this, 'register_custom_permalinks') );

				// This is to setup the link to render the custom permalink structures
				//add_filter('post_type_link', array(&$this, 'wp_hi_permalinks'), 10, 3);
				add_filter('term_link', array($this, 'wp_hi_term_link'), 10, 3);
    }

		function add_intranet_post_types() {

			foreach($this->cpts_array as $hi_cpt)
			{
				$labels = array(
					'name'                  => _x( $hi_cpt['name'], 'Post Type General Name', 'text_domain' ),
					'singular_name'         => _x( $hi_cpt['name'], 'Post Type Singular Name', 'text_domain' ),
					'menu_name'             => __( $hi_cpt['name'], 'text_domain' ),
					'name_admin_bar'        => __( $hi_cpt['name'], 'text_domain' ),
				);

				$capabilities = array(
					'publish_posts' => 'publish_'.$hi_cpt['cpt_name'],
					'edit_published_posts' => 'edit_published_'.$hi_cpt['cpt_name'],
					'edit_posts' => 'edit_'.$hi_cpt['cpt_name'],
					'edit_others_posts' => 'edit_others_'.$hi_cpt['cpt_name'],
					'delete_posts' => 'delete_'.$hi_cpt['cpt_name'],
					'delete_others_posts' => 'delete_others_'.$hi_cpt['cpt_name'],
					'read_private_posts' => 'read_private_'.$hi_cpt['cpt_name'],
					'edit_post' => 'edit_'.$hi_cpt['cpt_name'],
					'delete_post' => 'delete_'.$hi_cpt['cpt_name'],
					'read_post' => 'read_'.$hi_cpt['cpt_name'],
				);

				$args = array(
					'label'                 => __( $hi_cpt['name'], 'text_domain' ),
					'description'           => __( $hi_cpt['name'], 'text_domain' ),
					'labels'                => $labels,
					'supports'              => array( 'title', 'editor', 'excerpt', 'author', 'revisions', 'page-attributes', 'post-formats', ),
					'rewrite' 							=> array('slug' => $hi_cpt['cpt_slug']),
					'hierarchical'          => true,
					'public'                => true,
					'show_ui'								=> true,
					'menu_position'         => 5,
					'menu_icon'             => 'dashicons-admin-page',
					'can_export'            => true,
					'has_archive'           => true,
					'exclude_from_search'   => false,
					'capability_type'       => 'page',
					'capabilities'					=> $capabilities,

				);

				register_post_type( $hi_cpt['cpt_name'], $args );

			}

		}

		function add_intranet_taxonomies()
		{

			foreach($this->cpts_array as $hi_cpt)
			{

				$labels = array(
					'name'                       => _x( $hi_cpt['name'].' categories', 'Taxonomy General Name', 'text_domain' ),
					'singular_name'              => _x( $hi_cpt['name'].' category', 'Taxonomy Singular Name', 'text_domain' ),
					'menu_name'                  => __( $hi_cpt['name'].' categories', 'text_domain' ),
				);

				$args = array(
					'labels'                     => $labels,
					'rewrite' 									 => array('slug' => $hi_cpt['cpt_slug']),
					'hierarchical'               => true,
					'public'                     => true,
					'show_ui'                    => true,
					'show_admin_column'          => true,
					'show_in_nav_menus'          => true,
					'show_tagcloud'              => true,
				);

				register_taxonomy( $hi_cpt['cpt_taxonomy'], array( $hi_cpt['cpt_name'] ), $args );

			}

		}

		public function register_custom_permalinks()
		{
				foreach($this->cpts_array as $hi_cpt)
				{
						global $wp_rewrite;

						$wp_rewrite->add_rewrite_tag('%'.$hi_cpt['cpt_taxonomy'].'%', '([^/]+)', $hi_cpt['cpt_taxonomy'].'=');
						$wp_rewrite->add_permastruct($hi_cpt['cpt_taxonomy'], $hi_cpt['cpt_slug'].'/category/%'.$hi_cpt['cpt_taxonomy'].'%/', false);
				}
		}

		public function wp_hi_term_link($termlink, $term, $taxonomy) {

			foreach($this->cpts_array as $hi_cpt)
			{
	        $termlink = str_replace( $hi_cpt['cpt_taxonomy'], $hi_cpt['cpt_slug'].'/category', $termlink);
	        return $termlink;
			}

		}
}
