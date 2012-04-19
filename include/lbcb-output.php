<?php
/**
 * LB Colorbox Output Functions
 *
 * Useful functions for getting the colorboxes out of WordPress and into the real world.
 *
 * @package		LB-Colorbox
 * @copyright	Copyright (c) 2012, Doug Stewart
 * @license		http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU General Public License, v2 (or newer)
 *
 * @since 		LB-Colorbox 0.5
 *
 */

/**
 * Output the complete contents of a Colorbox swatch custom post
 *
 * @param string $content
 * @return string $content
 */
function lbcb_output_colorbox_post( $content ){
	global $post;
	$cb_content = '';
	
	if( 'colorbox' == get_post_type() ){
		$hexes = array();
		$lbcb_author = get_post_meta( $post->ID, '_lbcb_author', true);
		$lbcb_url = get_post_meta( $post->ID, '_lbcb_link', true);
		
		$cb_content .= '<div class="lbcb-swatch-wrapper">' . "\n";
		for( $i = 1; $i <= 5; $i++ ){
			$c_tmp = '_lbcb_color' . $i;
			$hex = $lbcb_post_meta = get_post_meta( $post->ID, $c_tmp, true );
			$cb_content .= '<div class="lbcb-swatch" style="background: ' . $hex . ';"></div>' . "\n";
			$hexes[] = $hex;
		}
		$cb_content .= '</div><!-- .lbcb-swatch-wrapper -->' . "\n";
		$cb_content .= '<div class="lbcb-meta-wrapper">' . "\n";
		if( !empty($lbcb_author) ){
			$cb_content .= '<div class="lbcb-author-wrapper"><span class="authortitle">Author:</span> ' . $lbcb_author . '</div>' . "\n";
		}
		if( !empty($lbcb_url) ){
			$cb_content .= '<div class="lbcb-link-wrapper"><span class="linktitle">Source:</span> ' . make_clickable( $lbcb_url ) . '</div>' . "\n";
		}
		$cb_content .= '<div class="lbcb-hex-wrapper"><span class="hextitle">Hex values:</span> ' . implode( ', ', $hexes ) . '</div>' . "\n";
		$cb_content .= '</div><!-- .lbcb-meta-wrapper -->' . "\n";
		
		if( is_archive() ){
			echo $cb_content;
		}else{
			$content = $cb_content . $content;
		}
	}
	return $content;
}
add_filter( 'the_content', 'lbcb_output_colorbox_post' );

/**
 * Output a specific set of Colorbox color swatches.
 *
 * @param string $cb_size The requested size of the swatch output
 * @param string $cb_echo Whether to output the swatch or return it
 * @return string $cb_content
 */
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

/**
 * Shortcode to generate Colorbox swatches
 *
 * @param array $atts
 */
function lbcb_colorbox_shortcode( $atts ){
	global $wpdb;
	extract( shortcode_atts( array(
			'size'	=> 'regular',
			'name'	=> '',
			'slug'	=> '',
			'id'	=> '',
			''
			), $atts ));
	
	$lbcb_args = array(
		'order'				=> 'DESC',
		'post_type'			=> 'colorbox',
		'post_status'		=> 'publish'
	);
	
	if( !empty($name))
		$lbcb_posts = $wpdb->get_col( "select ID from $wpdb->posts where post_title LIKE '" . $name . "%' AND post_status = 'publish'" );
	elseif( !empty($slug) )
		$lbcb_args['name'] = $slug;
	elseif( !empty($id) )
		$lbcb_args['p'] = $id; 

	$lbcb_query = new WP_Query( $lbcb_args );
	
	while( $lbcb_query->have_posts()) {
		$lbcb_query->the_post();
		the_title();
	}
}
add_shortcode( 'colorbox', 'lbcb_colorbox_shortcode' );