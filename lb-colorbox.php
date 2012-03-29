<?php
/**
 * Plugin Name: LB ColorBox
 * Plugin URI: http://literalbarrage.org/blog/code/colorbox
 * Description: Store custom color swatches as custom posts for fun and profit (and use in themes!).
 * Version: 0.1
 * Author: Doug Stewart
 * Author URI: http://literalbarrage.org/blog/
 *
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
*
* LB Colorbox Main
*
* @package		LB-Colorbox
* @copyright	Copyright (c) 2012, Doug Stewart
* @license		http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU General Public License, v2 (or newer)
*
* @since 		LB-Colorbox 0.5
*
* Stubbed-out loader.
*/
if( is_admin() ){
	include( 'include/lbcb-options.php' );
	include( 'include/lbcb-list-table.php' );
}
include( 'include/lbcb-cpt.php' );
include( 'include/KulerPHP/Kuler/Api.php' );
include( 'include/lbcb-kuler.php' );
include( 'include/lbcb-output.php' );

// $ratedk = lbcb_get_kulers( 'rating' );
// $popk = lbcb_get_kulers( 'popular' );

foreach($ratedk as $rks){
	// echo "<pre>";
	// var_dump($rks);
	// echo "</pre>";
	//var_dump($rks);
	//lbcb_insert_colorbox( $rks );
}

function lbcb_enqueue_styles(){
	//if( ( 'colorbox' == get_post_type() ) ){
		wp_enqueue_style( 'lb-colorbox', plugin_dir_url(__FILE__) . 'include/css/lbcb-core.css', '', '', 'screen' );
	//}
}
add_action( 'wp_enqueue_scripts', 'lbcb_enqueue_styles', 11 );
add_action( 'admin_print_styles', 'lbcb_enqueue_styles', 11 );