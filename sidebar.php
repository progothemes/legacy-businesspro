<?php
/**
 * The Sidebar containing the primary and secondary widget areas.
 *
 * @package ProGo
 * @subpackage Business Pro
 * @since Business Pro 1.0
 */
 
/* When we call the dynamic_sidebar() function, it'll spit out
 * the widgets for that widget area. If it instead returns false,
 * then the sidebar simply doesn't exist, so we'll hard-code in
 * some default sidebar stuff just in case.
 */
$sidebar = '';
if ( is_page() ) {
	global $post;
	$sidebar = get_post_meta($post->ID,'_progo_sidebar', true );
}
if ( $sidebar == '' ) {
	$sidebar = 'main';
	if ( is_front_page() ) {
		$sidebar = 'home';
	}
}
if ( ! dynamic_sidebar( $sidebar ) ) :
	
$showdefaults = true;
$options = get_option( 'progo_options' );
if ( isset( $options['hidedshare'] ) ) if ( $options['hidedshare'] == 1 ) {
	$showdefaults = false;
}
if ( $showdefaults ) {
?>
<div class="block share">
    <h3 class="title"><span class="spacer">Share</span></h3>
    <div class="inside">
        <?php
        if (function_exists('sharethis_button')) {
			sharethis_button();
		} else {
        /* no more fb_share?
		<a name="fb_share" type="icon" href="http://www.facebook.com/sharer.php">Share</a><script src="http://static.ak.fbcdn.net/connect.php/js/FB.Share" type="text/javascript"></script>
        */
        ?>
        <a href="http://twitter.com/share?url=<?php echo urlencode(get_permalink($post->ID)); ?>&amp;text=Check%20Out%20This%20Great%20Product!%20" class="twitter" target="_blank">Tweet</a>
		<?php
			if ( current_user_can('edit_theme_options') ) {
				$options = get_option( 'progo_options' );
				if ( (int) $options['showtips'] == 1 ) {
					echo '<a style="position: relative" class="ptip" href="'. admin_url('themes.php?page=theme_options#recommended') .'"><span>Install the ShareThis plugin to add more SHARE functionality</span></a>';
				}
			}
		}
        ?>
    </div>
</div>
<?php
}
endif; // end primary widget area ?>