<?php

function lbcb_add_swatch_column( $columns ){
	$columns = array("title"=> "Title", 
			"swatches" => "Swatches",
			"tags" => "Tags",  
			"comments" => '<div class="vers"><img alt="Comments" src="' . site_url() . '/wp-admin/images/comment-grey-bubble.png" /></div>',
			"date" => "Date" );
	return $columns;
}
add_filter( 'manage_edit-colorbox_columns', 'lbcb_add_swatch_column' );

function lbcb_show_swatch_column( $name ){
	global $post;

	switch( $name ){
		case "swatches":
			lbcb_swatches( 'mini', true );
		break;
	}
}
add_filter( 'manage_posts_custom_column', 'lbcb_show_swatch_column' );

function lbcb_create_menu_page(){
		add_options_page( 'ColorBox Settings', 'ColorBox', 'manage_options', 'lbcb-options', 'lbcb_options_page' );
}
add_action( 'admin_menu', 'lbcb_create_menu_page' );

function lbcb_kuler_menu_page(){
		add_submenu_page( 'edit.php?post_type=colorbox', 'Get Kuler Swatches', 'Kuler Swatches', 'edit_posts', 'lbcb_kulers' );
}
add_action( 'admin_menu', 'lbcb_kuler_menu_page' );

function lbcb_options_page(){
?>
	<div class="wrap">
	<form method="post" action="options.php">
	<?php settings_fields( 'lbcb_options_page' ); ?>
	<?php screen_icon(); ?>
	<h2 class="updatehook">LB Colorbox settings</h2>
	<table class="form-table">
	<tbody>

<?php do_settings_sections( 'lbcb_options_page' ); ?>

	</tbody>
	</table>

	<p class="submit">
	<?php lbcb_print_option_buttons(); ?>
	</p>
	</form>
<?php
}

function lbcb_admin_init(){
	register_setting( 'lbcb_options_page', 'lbcb_options_page', 'lbcb_settings_validate' );
	add_settings_section( 'lbcb_kuler_section', 'Kuler Options', 'lbcb_kuler_header', 'lbcb_options_page' );
	add_settings_field( 'lbcb_kuler_api_key', 'Kuler API Key', 'lbcb_print_kuler_api', 'lbcb_options_page', 'lbcb_kuler_section' );
}
add_action( 'admin_init', 'lbcb_admin_init' );

function lbcb_print_kuler_api( $name ){
	$settings = get_option( 'lbcb_settings_fields' );
	var_dump($settings);
	echo '<input type="text" name="kuler_api_key" id="lbcb_options_page[kuler_api_key]" />';
	echo '<br /><br /><span class="description"><a href="http://kuler.adobe.com/api/">Kuler</a> is a color palette-sharing web site/service from Adobe. You can sign up for an API key <a href="http://kuler.adobe.com/api/">right here</a>.';
}

function lbcb_print_option_buttons(){
?>
				<input class="button-primary" type="submit" name="lbcb_options[save]" id="lbcb_options[save]" value="<?php _e( 'Save Changes', 'lbcolorbox' ) ?>"/>
				<input class="button-secondary" type="submit" name="lbcb_options[reset]" id="lbcb_options[reset]" value="<?php _e( 'Reset To Defaults', 'lbcolorbox' ) ?>"/>

<?php
}

function lbcb_settings_validate( $input ){
	echo "INPUT: " . $input;
	echo "AKSJDASJDOASJDOASIJDOASJDOAISJDOASIJDOIASJDOIAJSDOIJASOIHDOSAD<br /><br />";
		echo "AKSJDASJDOASJDOASIJDOASJDOAISJDOASIJDOIASJDOIAJSDOIJASOIHDOSAD<br /><br />";
			echo "AKSJDASJDOASJDOASIJDOASJDOAISJDOASIJDOIASJDOIAJSDOIJASOIHDOSAD<br /><br />";
}