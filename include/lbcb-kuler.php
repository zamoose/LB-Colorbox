<?php
/**
 * Responsible for retrieving Kuler swatches from Adobe's API.
 */
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

/**
 * Responsible for outputting a given set of Kulers.
 */
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