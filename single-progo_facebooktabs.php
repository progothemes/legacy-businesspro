<?php
/**
 * @package ProGo
 * @subpackage Business Pro
 * @since Business Pro 1.3.0
 */
global $post;
$fbt = absint(get_post_meta($post->ID, '_FB_Tab_meta_template', true));
if ( $fbt < 2 || $fbt > 6 ) {
	$fbt = 1;
}



include 'facebook-tab-page'. $fbt .'.php';
?>