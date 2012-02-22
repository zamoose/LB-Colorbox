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

if( is_admin() ){
	include( 'include/lbcb-options.php' );
}
include( 'include/KulerPHP/Kuler/Api.php' );

function lbcb_output_colorbox_post( $content ){
	global $post;
	$cb_content = '';
	
	if( 'colorbox' == get_post_type() ){
		 lbcb_kulers_out('rating','radio');
		 		lbcb_kulers_out('popular','table');
		 		lbcb_kulers_out('recent','divs');
		
		$hexes = array();
		$lbcb_author = get_post_meta( $post->ID, '_lbcb_author', true);
		
		
		$cb_content .= '<div class="lbcb-swatch-wrapper">' . "\n";
		for( $i = 1; $i <= 5; $i++ ){
			$c_tmp = '_lbcb_color' . $i;
			$hex = $lbcb_post_meta = get_post_meta( $post->ID, $c_tmp, true );
			$cb_content .= '<div class="lbcb-swatch" style="background: ' . $hex . ';"></div>' . "\n";
			$hexes[] = $hex;
		}
		$cb_content .= '</div><!-- .lbcb-swatch-wrapper -->' . "\n";
		$cb_content .= '<div class="lbcb-meta-wrapper">' . "\n";
		$cb_content .= '<div class="lbcb-author-wrapper"><span class="authortitle">Author:</span> ' . $lbcb_author . '</div>' . "\n";
		$cb_content .= '<div class="lbcb-hex-wrapper"><span class="hextitle">Hex values:</span> ' . implode( ', ', $hexes ) . '</div>' . "\n";
		$cb_content .= '</div><!-- .lbcb-meta-wrapper -->' . "\n";
		
		if( is_archive() ){
					//echo $cb_content;
		}else{
			$content = $cb_content . $content;
		}
	}
	return $content;
}
add_filter( 'the_content', 'lbcb_output_colorbox_post' );

function lbcb_swatches( $cb_size = "regular", $cb_echo = true ){
	global $post;
	$cb_content = '';
	
	if( "regular" == $cb_size){
		$cb_content .= '<div class="lbcb-swatch-wrapper">' . "\n";
	}else{
		$cb_content .= '<div class="lbcb-mini-swatch-wrapper">' . "\n";
	}
	
	for( $i = 1; $i <= 5; $i++ ){
		$c_tmp = '_lbcb_color' . $i;
		$hex = get_post_meta( $post->ID, $c_tmp, true );
		$cb_content .= '<div class="lbcb-swatch" style="background: ' . $hex . ';"></div>' . "\n";
	}
	
	$cb_content .= '</div>';
	
	if( $cb_echo ){
		echo $cb_content;
	}else{
		return $cb_content;
	}
	
	return;
}

function lbcb_kulers_out( $kuler_type = 'rating', $output_type = 'divs' ){
	$kulers = lbcb_get_kulers( $kuler_type );
	
	switch ($output_type) {
		case 'select':
			echo '<select id="kulers_select">';
			
			foreach($kulers as $kuler){
				echo '<option value="' . $kuler['title'] . '">' . $kuler['title'] . '<div class="lbcb-mini-swatch-wrapper">';
				for( $i = 1; $i<= 5; $i++ ){
					echo '<div class="lbcb-mini-swatch" style="background:' . $kuler['color'.$i] . '"></div>';
				}
				echo '</div></option>';
			}
			echo '</select>';
		break;
		
		case 'radio':
			foreach($kulers as $kuler){
				echo '<input type="radio" value="' . $kuler['title'] . '" id="' . $kuler['title'] . '">' . $kuler['title'] . '<div class="lbcb-mini-swatch-wrapper">';
				for( $i = 1; $i<= 5; $i++ ){
					echo '<div class="lbcb-mini-swatch" style="background:' . $kuler['color'.$i] . '"></div>';
				}
				echo '</div><br />';
			}
		break;
		
		case 'table':
			echo '<table class="lbcb-kuler-table">';
			echo '<tr><th>Title</th><th>Author</th><th>Swatches</th></tr>';
			foreach($kulers as $kuler){
				echo '<tr><td>' . $kuler['title'] . '</td><td>'. $kuler['author'] . '</td><td><div class="lbcb-mini-swatch-wrapper">';
				for( $i = 1; $i<= 5; $i++ ){
					echo '<div class="lbcb-mini-swatch" style="background:' . $kuler['color'.$i] . '"></div>';
				}
				echo '</div></td></tr>';
			}
			echo '</table>';
		break;
		case 'divs':
		default:
			foreach($kulers as $kuler){
				echo '<div class="lbcb-kuler">Title: ' . $kuler['title'];
				echo '<div class="lbcb-mini-swatch-wrapper">';
				for( $i = 1; $i<= 5; $i++ ){
					echo '<div class="lbcb-mini-swatch" style="background:' . $kuler['color'.$i] . '"></div>';
				}
				echo '</div></div>';
			}
		break;
	}
}

function lbcb_get_kulers( $kuler_type = "rating" ){
	$kuler_trans = get_transient( 'lbcb_' . $kuler_type . '_kulers' );

	if( empty($kuler_trans) ){
		$kuler_api_key = "9E7F91134BFC9D170BFB8325C3548076";
		$kuler = new Kuler_Api( $kuler_api_key );
		
		$kuler_tmp = $kuler->get( $kuler_type );
		$hr_k = array();

		foreach( $kuler_tmp as $ra_k ){
			$hr_swatch = $ra_k->getSwatchesHex();

			$hr_k[] = array(	"title" 	=> $ra_k->getTitle(), 
								"author" 	=> $ra_k->getAuthorLabel(),
								"url"		=> $ra_k->getUrl(),
								"color1"	=> $hr_swatch[0],
								"color2"	=> $hr_swatch[1],
								"color3"	=> $hr_swatch[2],
								"color4"	=> $hr_swatch[3],
								"color5"	=> $hr_swatch[4],
			);
		}
		
		$kuler_trans = $hr_k;
		set_transient( 'lbcb_' . $kuler_type . '_kulers', $kuler_trans, 60*60*24*5 );
	}
	
	return $kuler_trans;
}

$ratedk = lbcb_get_kulers( 'rating' );

foreach($ratedk as $rks){
	//var_dump($rks);
	//lbcb_insert_colorbox( $rks );
}

function lbcb_insert_colorbox( $colorbox = array() ){
	//$lbcb_query = new WP_Query( array('post_type' => 'colorbox', '') );
	$post = array(
		'post_type'		=> 'colorbox',
		'post_title'	=> $colorbox['title'],
		'post_status'	=> 'draft',
	);
	
	$cb_ID = wp_insert_post( $post );
	if( $cb_ID != 0 ){
		add_post_meta( $cb_ID, '_lbcb_author', $colorbox['author'], true );
		add_post_meta( $cb_ID, '_lbcb_url', $colorbox['url'], true );
		add_post_meta( $cb_ID, '_lbcb_color1', $colorbox['color1'], true );
		add_post_meta( $cb_ID, '_lbcb_color2', $colorbox['color2'], true );
		add_post_meta( $cb_ID, '_lbcb_color3', $colorbox['color3'], true );
		add_post_meta( $cb_ID, '_lbcb_color4', $colorbox['color4'], true );
		add_post_meta( $cb_ID, '_lbcb_color5', $colorbox['color5'], true );
	}
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
	if( ( 'colorbox' == get_post_type() ) ){
		wp_enqueue_style( 'lb-colorbox', plugin_dir_url(__FILE__) . 'include/css/lbcb-core.css', '', '', 'screen' );
	}
}
add_action( 'wp_enqueue_scripts', 'lbcb_enqueue_styles', 11 );
add_action( 'admin_print_styles', 'lbcb_enqueue_styles', 11 );