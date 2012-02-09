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

include( 'include/KulerPHP/Kuler/Api.php' );

function lbcb_output_colorbox_post(){
	global $post;
	if( 'colorbox' == get_post_type() ){
		$lbcb_post_meta = get_post_meta( $post->ID );
		$hexes = array();
		
		echo '<div class="lbcb-swatch-wrapper">';
		for( $i = 1; $i <= 5; $i++ ){
			$c_tmp = '_lbcb_color' . $i;
			$hex = $lbcb_post_meta[$c_tmp][0];
			echo '<div class="lbcb-swatch" style="background: ' . $hex . ';"></div>';
			$hexes[] = $hex;
		}
		echo '</div>';
		
		echo '<div class="lbcb-hex-wrapper"><span class="hextitle">Hex values:</span> ';
		echo implode( ', ', $hexes );
		echo '</div>';
	}
}
add_filter( 'the_content', 'lbcb_output_colorbox_post' );

// $hr_k = array(
// 	array( 'title' => "Beetle Bus goes Jamba Juice!", "author" => "dianesternberg", "hexes" => array('#730046', '#BFBB11', '#FFC200', '#E88801', '#C93C00')),
// );
// 
// $popular_k = array(
// 	array( 'title' => 'Honey Pot', "author" => 'dezi9er', "hexes" => array( '#105B63', '#FFFAD5', '#FFD34E', '#DB9E36', '#BD4932') ),
// );

$popular_t = get_transient( 'lbcb_popular_kulers' );
$recent_t = get_transient( 'lbcb_recent_kulers' );


function lbcb_get_kulers( $kuler_type = "rating" ){
	$kuler_api_key = "9E7F91134BFC9D170BFB8325C3548076";
	$kuler = new Kuler_Api( $kuler_api_key );
	
	$kuler_trans = get_transient( 'lbcb_' . $kuler_type . '_kulers' );
	if( empty($kuler_trans) ){
		$kuler_tmp = $kuler->get( $kuler_type );
		$hr_k = array();

		for( $kuler_tmp as $ra_k ){
			$swatches = $ra_k->getSwatchesHex();

			$hr_k[] = array(	"title" 	=> $ra_k->title, 
								"author" 	=> $ra_k->author->authorID,
								"url"		=> $ra_k->getUrl(),
								"color1"	=> $hr_swatch[0],
								"color2"	=> $hr_swatch[1],
								"color3"	=> $hr_swatch[2],
								"color4"	=> $hr_swatch[3],
								"color5"	=> $hr_swatch[4],
			);
		}

		$kuler_trans = $hr_k;
		set_transient( 'lbcb_' . $kuler_type . '_kulers', $kuler_trans, 60*60*24*2 );
	}
	
	return $kuler_trans;
}

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


function lbcb_enqueue_styles(){
	if( !is_admin() && ( 'colorbox' == get_post_type() ) ){
		wp_enqueue_style( 'lb-colorbox', plugin_dir_url(__FILE__) . 'include/css/lbcb-core.css', '', '', 'screen' );
	}
}
add_action( 'wp_enqueue_scripts', 'lbcb_enqueue_styles', 11 );