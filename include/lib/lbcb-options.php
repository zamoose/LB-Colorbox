<?php

function lbcb_create_menu_page(){
	add_options_page( 'ColorBox Settings', 'ColorBox Settings Page', 'manage_options', 'plugin', 'lbcb_options_page' );
}
add_action( 'admin_menu', 'lbcb_create_menu_page' );

function lbcb_options_page(){
?>
	<div class="wrap">
	<form method="post" action="options.php">
	<?php screen_icon(); ?>
	<h2 class="updatehook">LB Colorbox settings <?php lbcb_print_option_buttons(); ?></h2>
<?php


	if ( isset( $_REQUEST['settings-updated'] ) ) echo '<div id="message" class="updated under-h2"><p><strong>'.$lblg_themename.' settings updated.</strong></p></div>';
?>
	<table class="form-table">
	<tbody>

	<?php lbcb_option_wrapper_header("Kuler API key:"); ?>

	</tbody>
	</table>

	<p class="submit">
	<?php lbcb_print_option_buttons(); ?>
	</p>
	</form>
<?php
}

function lbcb_print_option_buttons(){
?>
				<input class="button-primary" type="submit" name="lbcb_options[save]" id="lbcb_options[save]" value="<?php _e( 'Save Changes', 'lbcolorbox' ) ?>"/>
				<input class="button-secondary" type="submit" name="lbcb_options[reset]" id="lbcb_options[reset]" value="<?php _e( 'Reset To Defaults', 'lbcolorbox' ) ?>"/>

<?php
}

/**
 * Output the per-option table row header markup
 *
 * @param array $values
 */
function lbcb_option_wrapper_header( $values ){
	?>
	<tr valign="top"> 
	    <th scope="row"><?php echo $values; ?>:</th>
	    <td>
	<?php
}

/**
 * Output the per-option table row footer markup
 *
 * @param array $values
 */
function lbcb_option_wrapper_footer( $values ){
	?>
		<br /><br />
		<span class="description"><?php echo $values['desc']; ?></span>
	    </td>
	</tr>
	<?php 
}
