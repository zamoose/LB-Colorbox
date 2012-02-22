<?php

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
		require_once 'metaboxes/init.php';

}
add_action( 'init', 'lbcb_initialize_cmb_meta_boxes', 9999 );

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