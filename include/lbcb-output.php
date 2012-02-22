<?php
function lbcb_output_colorbox_post( $content ){
	global $post;
	$cb_content = '';
	
	if( 'colorbox' == get_post_type() ){
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
add_filter( 'the_excerpt', 'lbcb_output_colorbox_post' );


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