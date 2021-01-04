<?php

add_action( 'wp_enqueue_scripts', 'insert_css' );

function insert_css() {

	//########## Import all the stylesheets here like the example below ##########
	//wp_register_style( 'ploume', 'https://source.ploume.io/latest/css/ploume.css' );
	//wp_enqueue_style( 'ploume' );


	// Import the WordPress stylesheet (style.css)
	wp_enqueue_style( 'style', get_stylesheet_uri() );

	//########## Import all the javascript scripts here like the example below ##########
	//wp_register_script( 'jquery2', 'https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js' );
	//wp_enqueue_script( 'jquery2' );
}

add_theme_support( 'menus' );

register_nav_menus( [
	'main-menu'  => 'Main navigation',
	'footer-menu'     => 'Footer navigation'
] );

add_theme_support( 'post-thumbnails' );

function create_post_type() {
	register_post_type( 'items',
		array(
			'label'              => __( 'Items' ),
			'singular_label'     => __( 'Item' ),
			'add_new_item'       => __( 'Add item' ),
			'edit_item'          => __( 'Edit item' ),
			'new_item'           => __( 'New item' ),
			'view_item'          => __( 'View item' ),
			'search_items'       => __( 'Search items' ),
			'not_found'          => __( 'Not found réalisation trouvée' ),
			'not_found_in_trash' => __( 'Not found in trash' ),
			'public'             => true,
			'show_ui'            => true,
			'capability_type'    => 'post',
			'has_archive'        => true,
			'hierarchical'       => true,
			'menu_icon'          => 'dashicons-screenoptions',
			'taxonomies'         => array( 'types' ),
			'supports'           => array( 'title', 'editor', 'thumbnail' ),
			'rewrite'            => array( 'slug' => 'items', 'with_front' => true )
		)
	);
}

add_action( 'init', 'create_post_type' );
