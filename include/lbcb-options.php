<?php
/**
 * LB Colorbox Options, Etc.
 *
 * @package		LB-Colorbox
 * @copyright	Copyright (c) 2012, Doug Stewart
 * @license		http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU General Public License, v2 (or newer)
 *
 * @since 		LB-Colorbox 0.5
 *
 * Admin/back-end functionality of LB Colorbox is encapsulated in this file.
 */


/**
 * Register the settings to be output and saved on the options page.
 */
function lbcb_admin_init(){
	register_setting( 'lbcb_options_group', 'lbcb_options', 'lbcb_options_validate' );
	add_settings_section( 'lbcb_kuler_section', 'Kuler Options', 'lbcb_kuler_header', 'lbcb_options_group' );
	add_settings_field( 'lbcb_kuler_api_key', 'Kuler API Key', 'lbcb_print_kuler_api', 'lbcb_options_group', 'lbcb_kuler_section' );
}
add_action( 'admin_init', 'lbcb_admin_init' );

/**
 * Register the admin page.
 */
function lbcb_create_menu_page(){
		add_options_page( 'ColorBox Settings', 'ColorBox', 'manage_options', 'lbcb-options', 'lbcb_options_page' );
}
add_action( 'admin_menu', 'lbcb_create_menu_page' );

/**
 * Options page output callback function.
 */
function lbcb_options_page(){
?>
	<div class="wrap">
	<form method="post" action="options.php">
	<?php settings_fields( 'lbcb_options_group' ); ?>
	<?php screen_icon(); ?>
	<h2 class="updatehook"><?php _e('LB Colorbox settings', 'lbcb_textdomain' ); ?></h2>
	<table class="form-table">
	<tbody>

	<?php do_settings_sections( 'lbcb_options_group' ); ?>

	</tbody>
	</table>

	<p class="submit">
	<?php lbcb_print_option_buttons(); ?>
	</p>
	</form>
<?php
}

/**
 * Callback to output the Kuler API key setting input.
 *
 * @param string $name
 */
function lbcb_print_kuler_api( $name ){
	$lbcb_options = get_option( 'lbcb_options' );
	?>
	<input type="text" name="lbcb_options[kuler_api_key]" id="lbcb_options[kuler_api_key]" value="<?php echo $lbcb_options['kuler_api_key']; ?>" /><br /><br />
	<span class="description"><a href="http://kuler.adobe.com/">Kuler</a> <?php _e(' is a color palette-sharing web site/service from Adobe. You can sign up for an API key ', 'lbcb_textdomain' ); ?><a href="http://kuler.adobe.com/api/"><?php _e('right here', 'lbcb_textdomain' ); ?></a>.</span>
	<?php
}

/**
 * Output the Save/Reset buttons
 */
function lbcb_print_option_buttons(){
?>
	<input class="button-primary" type="submit" name="lbcb_options[save]" id="lbcb_options[save]" value="<?php _e( 'Save Options', 'lbcb_textdomain' ); ?>"/>
	<input class="button-secondary" type="submit" name="lbcb_options[reset]" id="lbcb_options[reset]" value="<?php _e( 'Reset To Defaults', 'lbcb_textdomain' ); ?>"/>

<?php
}

/**
 * Validate the settings entries passed to us.
 * 
 * @param array $input
 * @return array
 */
function lbcb_options_validate( $input ){
	$valid_input = get_option( 'lbcb_options' );
	
	$valid_input['kuler_api_key'] = esc_html( $input['kuler_api_key'] );
	
	return $valid_input;
}

/**
 * Filter the CPT listing table and add a few columns that help organize 
 * the ColorBoxes better.
 *
 * @param array $columns
 * @return array
 */
function lbcb_add_swatch_column( $columns ){
	$columns = array("title"=> "Title", 
			"swatches" => "Swatches",
			"tags" => "Tags",  
			"comments" => '<div class="vers"><img alt="Comments" src="' . site_url() . '/wp-admin/images/comment-grey-bubble.png" /></div>',
			"date" => "Date" );
	return $columns;
}
add_filter( 'manage_edit-colorbox_columns', 'lbcb_add_swatch_column' );

/**
 * Output the corresponding swatch per
 * CPT entry.
 *
 * @param string $name
 */
function lbcb_show_swatch_column( $name ){
	global $post;

	switch( $name ){
		case "swatches":
			lbcb_swatches( 'mini', true );
		break;
	}
}
add_filter( 'manage_posts_custom_column', 'lbcb_show_swatch_column' );
