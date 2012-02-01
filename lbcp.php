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
	//var_dump($feed);
	$feed_items = $feed->get_items(0,1);
	
	foreach( $feed_items as $item ){
		echo "<h2>Item</h2>";
		$title = $item->get_title();
		$content = $item->get_content();
		$description = $item->get_description();
		$tags = $item->get_item_tags( SIMPLEPIE_NAMESPACE_XML, "description" );
		$swatches = $item->themeSwatches;
		$child = $item->child;
		echo "<h3>" . $title . "</h3>";
		echo "<pre>";
		var_dump($description);
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
			array(
				'name' => 'Link',
				'desc' => 'Where did you find this color scheme?',
				'id'   => $prefix . 'link',
				'type' => 'text',
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
			array(
				'name' => 'Test Text Area',
				'desc' => 'field description (optional)',
				'id'   => $prefix . 'test_textarea',
				'type' => 'textarea',
			),
			array(
				'name' => 'Test Text Area Small',
				'desc' => 'field description (optional)',
				'id'   => $prefix . 'test_textareasmall',
				'type' => 'textarea_small',
			),
			array(
				'name' => 'Test Text Area Code',
				'desc' => 'field description (optional)',
				'id'   => $prefix . 'test_textarea_code',
				'type' => 'textarea_code',
			),
			array(
				'name' => 'Test Title Weeeee',
				'desc' => 'This is a title description',
				'id'   => $prefix . 'test_title',
				'type' => 'title',
			),
			array(
				'name'    => 'Test Select',
				'desc'    => 'field description (optional)',
				'id'      => $prefix . 'test_select',
				'type'    => 'select',
				'options' => array(
					array( 'name' => 'Option One', 'value' => 'standard', ),
					array( 'name' => 'Option Two', 'value' => 'custom', ),
					array( 'name' => 'Option Three', 'value' => 'none', ),
				),
			),
			array(
				'name'    => 'Test Radio inline',
				'desc'    => 'field description (optional)',
				'id'      => $prefix . 'test_radio_inline',
				'type'    => 'radio_inline',
				'options' => array(
					array( 'name' => 'Option One', 'value' => 'standard', ),
					array( 'name' => 'Option Two', 'value' => 'custom', ),
					array( 'name' => 'Option Three', 'value' => 'none', ),
				),
			),
			array(
				'name'    => 'Test Radio',
				'desc'    => 'field description (optional)',
				'id'      => $prefix . 'test_radio',
				'type'    => 'radio',
				'options' => array(
					array( 'name' => 'Option One', 'value' => 'standard', ),
					array( 'name' => 'Option Two', 'value' => 'custom', ),
					array( 'name' => 'Option Three', 'value' => 'none', ),
				),
			),
			array(
				'name'     => 'Test Taxonomy Radio',
				'desc'     => 'Description Goes Here',
				'id'       => $prefix . 'text_taxonomy_radio',
				'type'     => 'taxonomy_radio',
				'taxonomy' => '', // Taxonomy Slug
			),
			array(
				'name'     => 'Test Taxonomy Select',
				'desc'     => 'Description Goes Here',
				'id'       => $prefix . 'text_taxonomy_select',
				'type'     => 'taxonomy_select',
				'taxonomy' => '', // Taxonomy Slug
			),
			array(
				'name' => 'Test Checkbox',
				'desc' => 'field description (optional)',
				'id'   => $prefix . 'test_checkbox',
				'type' => 'checkbox',
			),
			array(
				'name'    => 'Test Multi Checkbox',
				'desc'    => 'field description (optional)',
				'id'      => $prefix . 'test_multicheckbox',
				'type'    => 'multicheck',
				'options' => array(
					'check1' => 'Check One',
					'check2' => 'Check Two',
					'check3' => 'Check Three',
				),
			),
			array(
				'name'    => 'Test wysiwyg',
				'desc'    => 'field description (optional)',
				'id'      => $prefix . 'test_wysiwyg',
				'type'    => 'wysiwyg',
				'options' => array(	'textarea_rows' => 5, ),
			),
			array(
				'name' => 'Test Image',
				'desc' => 'Upload an image or enter an URL.',
				'id'   => $prefix . 'test_image',
				'type' => 'file',
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
