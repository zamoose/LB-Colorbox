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
	if( isset($_POST['kuler'])){
		parse_str($_POST['kuler']);
		$response = $kuler;
		echo $response;
		die();
		
	}else{
		$response = "No Kuler sent!";
		echo $response;
		die();
	}
}
add_action( 'wp_ajax_kuler_response', 'lbcb_ajax_process_kulers' );