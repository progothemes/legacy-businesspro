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
	echo nl2br(wp_kses($options['headline'], array()));
} else {
	_e('Get Your Customers<br />What They Need Most!');
}
?></td></tr></table>
<?php echo apply_filters('the_content', $options['form']); ?>
</div>