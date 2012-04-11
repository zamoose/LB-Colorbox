<?php
/**
 * LB Colorbox Kuler Helper Functions
 *
 * Retrieval and display of Kuler API results are encapsulated in this file
 *
 * @package		LB-Colorbox
 * @copyright	Copyright (c) 2012, Doug Stewart
 * @license		http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU General Public License, v2 (or newer)
 *
 * @since 		LB-Colorbox 0.5
 *
 */


/**
 * Responsible for retrieving Kuler swatches from Adobe's API.
 *
 * @param string $kuler_type
 * @return array
 */
function lbcb_get_kulers( $kuler_type = "rating" ){
	set_exception_handler( 'lbcb_error_handler' );

	$kuler_trans = get_transient( 'lbcb_' . $kuler_type . '_kulers' );

	if( empty($kuler_trans) ){
		$lbcb_options = get_option( 'lbcb_options' );
		$kuler_api_key = $lbcb_options['kuler_api_key'];

		if( !empty( $kuler_api_key ) ){
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
									//"ID"		=> $ra_k->get->theme->ID,
				);
			}
		
			$kuler_trans = $hr_k;
			set_transient( 'lbcb_' . $kuler_type . '_kulers', $kuler_trans, 60*60*24*5 );
		}
	}
	
	restore_exception_handler();
	return $kuler_trans;
}

/**
 * Responsible for outputting a given set of Kulers.
 *
 * @param string $kuler_type
 * @param string $output_type
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
			echo '<tr><th>Title</th><th>Author</th><th>Link</th><th>Swatches</th></tr>';
			foreach($kulers as $kuler){
				echo '<tr><td>' . $kuler['title'] . '</td><td>'. $kuler['author'] . '</td>';
				echo '<td>' . make_clickable($kuler['url']) . '</td><td><div class="lbcb-mini-swatch-wrapper">';
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

/**
 * The Kuler library doesn't do a good job of catching its exceptions.
 * This helps out and at least prints a semi-useful message to the end user.
 *
 * @param object $exception
 */
function lbcb_error_handler( $exception ){
	echo "<div class=\"wrap\"><h2>Kuler Error</h2>";
	echo "<pre><code>" . $exception->getMessage() . "</code></pre>\n";
	echo "<p>This is most likely due to an inability to connect to Adobe's servers. Please check your Internet connection.</p></div>";
	return;
}