<?php
/**
 * Plugin Name: LB ColorBox
 * Plugin URI: http://literalbarrage.org/blog/code/colorbox
 * Description: Store custom color swatches as custom posts for fun and profit (and use in themes!).
 * Version: 0.1
 * Author: Doug Stewart
 * Author URI: http://literalbarrage.org/blog/
*/

/**
 * Copyright (c) 2012 Doug Stewart. All rights reserved.
 *
 * Released under the GPL license
 * http://www.opensource.org/licenses/gpl-license.php
 *
 * This is an add-on for WordPress
 * http://wordpress.org/
 *
 * **********************************************************************
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 * 
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 * **********************************************************************
 */

$kuler_api_key = "9E7F91134BFC9D170BFB8325C3548076";
$kuler_api_url = 'http://kuler-api.adobe.com/rss/get.cfm?listtype=';
$kuler_recent = 'recent&key=';

function lbcb_get_recent_kuler(){
	//$feed = fetch_feed(  $kuler_api_url . 'recent&key=' . $kuler_api_key );
	
	$feed = fetch_feed ( 'http://localhost:8888/recent.xml');
}

function lbcb_get_random_kuler(){
	$feed = fetch_feed( $kuler_api_url . 'random&key=' . $kuler_api_key );
}

function lbcb_get_popular_kuler(){
	//$feed = fetch_feed( $kuler_api_url . 'popular&timespan=30&key=' . $kuler_api_key );
	$feed = fetch_feed( 'http://localhost:8888/popular.xml');
	
	//print_r($feed);
	$feed_items = $feed->get_items(0,1);
	
foreach( $feed_items as $item ){
echo "<h2>Item</h2>";
$title = $item->get_title();
$content = $item->get_content();
	// 	$description = $item->get_description();
 	$tags = $item->get_item_tags( SIMPLEPIE_NAMESPACE_XML, "description" );
	// 	$swatches = $item->themeSwatches;
	// 	$child = $item->child;
		echo "<h3>" . $title . "</h3>";
		echo "<pre>";
		var_dump($content);
		var_dump($tags);
		echo "</pre>";
}
}
add_action( 'lblg_above_content_and_sidebars', 'lbcb_get_popular_kuler' );

/**
 * 
 */
function lbcb_add_colorbox_cpt(){
	register_post_type('ColorBox', 
		array(	'label' => 'ColorBoxes',
				'description' => '',
				'public' => true,
				'show_ui' => true,
				'show_in_menu' => true,
				'capability_type' => 'post',
				'hierarchical' => false,
				'rewrite' => array('slug' => ''),
				'query_var' => true,
				'has_archive' => true,
				'supports' => array('title','comments','thumbnail','author',),
				'taxonomies' => array('post_tag',),
				'labels' => array(
								  'name' => 'ColorBoxes',
								  'singular_name' => 'ColorBox',
								  'menu_name' => 'ColorBoxes',
								  'add_new' => 'Add ColorBox',
								  'add_new_item' => 'Add New ColorBox',
								  'edit' => 'Edit',
								  'edit_item' => 'Edit ColorBox',
								  'new_item' => 'New ColorBox',
								  'view' => 'View ColorBox',
								  'view_item' => 'View ColorBox',
								  'search_items' => 'Search ColorBoxes',
								  'not_found' => 'No ColorBoxes Found',
								  'not_found_in_trash' => 'No ColorBoxes Found in Trash',
								  'parent' => 'Parent ColorBox',
								),
			)
	);
}
add_action( 'init', 'lbcb_add_colorbox_cpt' );

/**
 * Define the metabox and field configurations.
 *
 * @param  array $meta_boxes
 * @return array
 */
function lbcb_metaboxes( array $meta_boxes ) {
	// Start with an underscore to hide fields from custom fields list
	$prefix = '_lbcb_';
	
	$meta_boxes[] = array(
		'id'         => 'lbcb_colors_metabox',
		'title'      => 'Colors',
		'pages'      => array( 'colorbox' ), // Post type
		'context'    => 'normal',
		'priority'   => 'high',
		'show_names' => true, // Show field names on the left
		'fields'     => array(
			// array(
			// 	'name' => 'Swatches',
			// 	'id'   => $prefix . 'swatches_title',
			// 	'type' => 'title',
			// ),
			array(
	            'name' => 'Color #1',
	            'id'   => $prefix . 'color1',
	            'type' => 'colorpicker',
	        ),
			array(
	            'name' => 'Color #2',
	            'id'   => $prefix . 'color2',
	            'type' => 'colorpicker',
	        ),
			array(
	            'name' => 'Color #3',
	            'id'   => $prefix . 'color3',
	            'type' => 'colorpicker',
	        ),
			array(
	            'name' => 'Color #4',
	            'id'   => $prefix . 'color4',
	            'type' => 'colorpicker',
	        ),
			array(
	            'name' => 'Color #5',
	            'id'   => $prefix . 'color5',
	            'type' => 'colorpicker',
	        ),
		),
	);
	$meta_boxes[] = array(
		'id'         => 'lbcb_info_metabox',
		'title'      => 'Information',
		'pages'      => array( 'colorbox' ), // Post type
		'context'    => 'normal',
		'priority'   => 'high',
		'show_names' => true, // Show field names on the left
		'fields'     => array(
			array(
				'name' => 'Link',
				'desc' => 'Where did you find this color scheme?',
				'id'   => $prefix . 'link',
				'type' => 'text',
			),
			array(
				'name'    => 'Type',
				'id'      => $prefix . 'type',
				'type'    => 'select',
				'options' => array(
					array( 'name' => 'Original work', 'value' => 'original', ),
					array( 'name' => 'ColourLover', 'value' => 'colourlover', ),
					array( 'name' => 'Kuler', 'value' => 'kuler', ),
					array( 'name' => 'StudioPress', 'value' => 'studiopress', ),
					array( 'name' => 'Other', 'value' => 'other', ),
				),
			),
			array(
				'name' => 'Author',
				'id'   => $prefix . 'author',
				'type' => 'text_medium',
			),
			array(
				'name' => 'Date',
				'id'   => $prefix . 'date',
				'type' => 'text_date',
			),
		),
	);

	// Add other metaboxes as needed

	return $meta_boxes;

}
add_filter( 'cmb_meta_boxes', 'lbcb_metaboxes' );

function lbcb_initialize_cmb_meta_boxes() {

	if ( ! class_exists( 'cmb_Meta_Box' ) )
		require_once 'include/metaboxes/init.php';

}
add_action( 'init', 'lbcb_initialize_cmb_meta_boxes', 9999 );
