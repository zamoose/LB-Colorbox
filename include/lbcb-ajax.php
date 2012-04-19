<?php
/**
 * LB Colorbox AJAX
 *
 * Set up and process AJAX requests, particularly in the admin back-end
 *
 * @package		LB-Colorbox
 * @copyright	Copyright (c) 2012, Doug Stewart
 * @license		http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU General Public License, v2 (or newer)
 *
 * @since 		LB-Colorbox 0.5
 *
 */

/**
 * Load the relevant AJAX JS
 */
function lbcb_ajax_load_scripts(){
	wp_enqueue_script( 'lbcb-ajax', plugin_dir_url(__FILE__) . '/js/lbcb-ajax.js', array('jquery') );
	wp_localize_script( 'lbcb-ajax', 'the_ajax_script', array( 'ajaxurl' => admin_url( 'admin-ajax.php' ) ) );
}
add_action( 'wp_print_scripts', 'lbcb_ajax_load_scripts' );

/**
 * Process the Kulers sent in the back-end
 */
function lbcb_ajax_process_kulers(){
	global $wpdb;

	// Set up some query arguments
	$lbcb_args = array(
		'order'				=> 'DESC',
		'post_type'			=> 'colorbox',
	);
	
	// Check $_POST to make sure we got sent legit data
	if( isset($_POST['kuler'])){
		parse_str($_POST['kuler']);

		// Grab the Kuler transient for the current screen
		$kuler_trans = get_transient( 'lbcb_' . $kuler_type . '_kulers' );
		
		$kuler_urls = wp_list_pluck( $kuler_trans, 'url' );
		
		if(in_array($kuler,$kuler_urls)){
			$k_index = array_search( $kuler, $kuler_urls);
			$k_full = $kuler_trans[$k_index];
			
			//print_r($k_full);
		}else{
			echo "Whoops. You shouldn't ever see this message.";
		}
		$lbcb_args['meta_query'] = array( 
										array(	'key'	=> '_lbcb_type',
												'value'	=> 'kuler'
										),
										array(	'key'	=> '_lbcb_link',
												'value'	=> $kuler
										)
									);
		$kuler_query = new WP_Query( $lbcb_args );
		
		if( 0 == $kuler_query->post_count){
			$k_full['type'] = 'kuler';
			lbcb_insert_colorbox( $k_full );
			echo "Kuler added.";
		}else{
			echo "Kuler already present.";
		}
		die();
		
	}else{
		$response = "No Kuler sent!";
		echo $response;
		die();
	}
}
add_action( 'wp_ajax_kuler_response', 'lbcb_ajax_process_kulers' );