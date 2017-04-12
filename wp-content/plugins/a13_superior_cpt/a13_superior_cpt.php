<?php
/**
 * Plugin Name: Apollo13 Superior Post Types
 * Description: Envato(ThemeForest) require to add custom post types in separate plugin, so here you go:-)
 * Version: 1.0.0
 * Author: Apollo13
 * Author URI: http://apollo13.eu/
 * License: GPL2
 */

/*
 * Register custom post type for special use
 */
if(!function_exists('a13_superior_cpt')){
	function a13_superior_cpt(){
		global $apollo13;
		
		/********** WORKS *********/
		$work_type = defined('A13_CUSTOM_POST_TYPE_WORK') ? A13_CUSTOM_POST_TYPE_WORK : 'work';
		$work_slug = defined('A13_CUSTOM_POST_TYPE_WORK_SLUG') ? A13_CUSTOM_POST_TYPE_WORK_SLUG : 'work';
		$work_tax  = defined('A13_CPT_WORK_TAXONOMY') ? A13_CPT_WORK_TAXONOMY : 'genre';
		
		$labels = array(
			'name' =>           __( 'My Works', 'a13_superior_cpt' ),
			'singular_name' =>  __( 'Work', 'a13_superior_cpt' ),
			'add_new' =>        __( 'Add New', 'a13_superior_cpt' ),
			'add_new_item' =>   __( 'Add New Work', 'a13_superior_cpt' ),
			'edit_item' =>      __( 'Edit Work', 'a13_superior_cpt' ),
			'new_item' =>       __( 'New Work', 'a13_superior_cpt' ),
			'view_item' =>      __( 'View Work', 'a13_superior_cpt' ),
			'search_items' =>   __( 'Search Works', 'a13_superior_cpt' ),
			'not_found' =>      __( 'Nothing found', 'a13_superior_cpt' ),
			'not_found_in_trash' => __( 'Nothing found in Trash', 'a13_superior_cpt' ),
			'parent_item_colon' => ''
		);

		$supports = array( 'title','thumbnail','editor' );
		//used with Superior Theme?
		if(isset($apollo13)){
			if($apollo13->get_option('cpt_work', 'comments') == 'on'){
				array_push($supports, 'comments');
			}
		}

		$args = array(
			'labels' => $labels,
			'public' => true,
			'query_var' => true,
			'menu_position' => 5,
			'rewrite' =>  array('slug' => $work_slug ),
			'supports' => $supports,
		);

		register_post_type( $work_type, $args );

		$genre_labels = array(
			'name' => __( 'Works Genres', 'a13_superior_cpt' ),
			'singular_name' => __( 'Genre', 'a13_superior_cpt' ),
			'search_items' =>  __( 'Search Genres', 'a13_superior_cpt' ),
			'popular_items' =>  __( 'Popular Genres', 'a13_superior_cpt' ),
			'all_items' => __( 'All Genres', 'a13_superior_cpt' ),
			'parent_item' => __( 'Parent Genre', 'a13_superior_cpt' ),
			'parent_item_colon' => __( 'Parent Genre:', 'a13_superior_cpt' ),
			'edit_item' => __( 'Edit Genre', 'a13_superior_cpt' ),
			'update_item' => __( 'Update Genre', 'a13_superior_cpt' ),
			'add_new_item' => __( 'Add New Genre', 'a13_superior_cpt' ),
			'new_item_name' => __( 'New Genre Name', 'a13_superior_cpt' ),
			'menu_name' => __( 'Genre', 'a13_superior_cpt' ),
		);

		register_taxonomy($work_tax, array($work_type),
			array(
				"hierarchical" => false,
				"label" => __( 'Works Genres', 'a13_superior_cpt' ),
				"labels" => $genre_labels,
				"rewrite" => true,
				'show_admin_column' => true
			)
		);

		

		/********** GALLERIES *********/
		$gallery_type = defined('A13_CUSTOM_POST_TYPE_GALLERY') ? A13_CUSTOM_POST_TYPE_GALLERY : 'gallery';
		$gallery_slug = defined('A13_CUSTOM_POST_TYPE_GALLERY_SLUG') ? A13_CUSTOM_POST_TYPE_GALLERY_SLUG : 'gallery';
		$gallery_tax  = defined('A13_CPT_GALLERY_TAXONOMY') ? A13_CPT_GALLERY_TAXONOMY : 'kind';
		
		$labels = array(
			'name' =>           __( 'Galleries', 'a13_superior_cpt' ),
			'singular_name' =>  __( 'Gallery', 'a13_superior_cpt' ),
			'add_new' =>        __( 'Add New', 'a13_superior_cpt' ),
			'add_new_item' =>   __( 'Add New Gallery', 'a13_superior_cpt' ),
			'edit_item' =>      __( 'Edit Gallery', 'a13_superior_cpt' ),
			'new_item' =>       __( 'New Gallery', 'a13_superior_cpt' ),
			'view_item' =>      __( 'View Gallery', 'a13_superior_cpt' ),
			'search_items' =>   __( 'Search Galleries', 'a13_superior_cpt' ),
			'not_found' =>      __( 'Nothing found', 'a13_superior_cpt' ),
			'not_found_in_trash' => __( 'Nothing found in Trash', 'a13_superior_cpt' ),
			'parent_item_colon' => ''
		);

		$args = array(
			'labels' => $labels,
			'public' => true,
			'query_var' => true,
			'menu_position' => 5,
			'rewrite' =>  array('slug' => $gallery_slug),
			'supports' => array( 'title','thumbnail' ),
		);


		register_post_type( $gallery_type , $args );

		$type_labels = array(
			'name' => __( 'Galleries Types', 'a13_superior_cpt' ),
			'singular_name' => __( 'Gallery type', 'a13_superior_cpt' ),
			'search_items' =>  __( 'Search Galleries Types', 'a13_superior_cpt' ),
			'popular_items' =>  __( 'Popular Galleries Types', 'a13_superior_cpt' ),
			'all_items' => __( 'All Galleries Types', 'a13_superior_cpt' ),
			'parent_item' => __( 'Parent Gallery type', 'a13_superior_cpt' ),
			'parent_item_colon' => __( 'Parent Gallery type:', 'a13_superior_cpt' ),
			'edit_item' => __( 'Edit Gallery type', 'a13_superior_cpt' ),
			'update_item' => __( 'Update Gallery type', 'a13_superior_cpt' ),
			'add_new_item' => __( 'Add New Gallery type', 'a13_superior_cpt' ),
			'new_item_name' => __( 'New Gallery type Name', 'a13_superior_cpt' ),
			'menu_name' => __( 'Gallery type', 'a13_superior_cpt' ),
		);

		register_taxonomy($gallery_tax, array($gallery_type),
			array(
				"hierarchical" => false,
				"label" => __( 'Galleries Types', 'a13_superior_cpt' ),
				"labels" => $type_labels,
				"rewrite" => true,
				'show_admin_column' => true
			)
		);

	}
}

add_action( 'init', 'a13_superior_cpt' );
