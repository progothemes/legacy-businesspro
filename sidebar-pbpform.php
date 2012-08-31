<?php
/**
 * Homepage Form stored in another Sidebar.
 *
 * @package ProGo
 * @subpackage Business Pro
 * @since Business Pro 1.0
 */

$options = get_option( 'progo_options' );
if ( is_array($options) == false ) {
	$options = array();
}
?>
<div class="pbpform"><a name="pbpform"></a>
<table class="tar" width="100%"><tr><td><?php
if ( isset( $options['headline'] ) ) {
	echo str_replace( '|', '<br />', wp_kses( $options['headline'], array() ) );
} else {
	_e('Get Your Customers<br />What They Need Most!');
}
?></td></tr></table>
<?php
$showformtip = true;
if ( isset( $options['form'] ) ) {
	if ( $options['form'] != "" ) {
		$showformtip = false;
		echo apply_filters('the_content', $options['form']);
	}
}
if ( $showformtip && current_user_can('edit_theme_options') ) {
	$progourl = admin_url('themes.php?page=theme_options#progo_homepage');
	echo '<div style="text-align: center"><p><br /></p><h3>Add a FORM to this area<br />by pasting a shortcode or Form HTML<br />into ProGo Theme\'s <a href="'. progourl .'"><strong>Form Code</strong> box</a></h3><p><a href="'. $progourl .'" class="button" style="margin:20px auto">Theme Options</a></p></div>';
}
?>
</div>