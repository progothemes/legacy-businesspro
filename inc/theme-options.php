<?php
/**
 * ProGo Small Business Pro Theme Options
 *
 * @package ProGo
 * @subpackage Business Pro
 * @since Business Pro 1.4.0
 */

/**
 * Register the form setting for our progo_options array.
 *
 * This function is attached to the admin_init action hook.
 *
 * This call to register_setting() registers a validation callback, progo_theme_options_validate(),
 * which is used when the option is saved, to ensure that our option values are complete, properly
 * formatted, and safe.
 *
 * @since Business Pro 1.3.0
 */
function progo_theme_options_init() {

	register_setting(
		'progo_options', // Options group, see settings_fields() call in progo_theme_options_render_page()
		'progo_options', // Database option, see progo_get_theme_options()
		'progo_validate_options' // The sanitization callback, see progo_validate_options()
	);
	
	add_settings_section( 'progo_api', 'ProGo Themes API Key', 'progo_section_text', 'progo_api' );
	add_settings_field( 'progo_api_key', 'API Key', 'progo_field_apikey', 'progo_api', 'progo_api' );
	
	add_settings_section( 'progo_theme', 'Theme Customization', 'progo_section_text', 'theme_options' );
	add_settings_field( 'progo_colorscheme', 'Color Scheme', 'progo_field_color', 'theme_options', 'progo_theme' );
	add_settings_field( 'progo_logo', 'Logo', 'progo_field_logo', 'theme_options', 'progo_theme' );

	add_settings_section( 'progo_info', 'General Site Information', 'progo_section_text', 'theme_options' );
	add_settings_field( 'progo_blogname', 'Site Name', 'progo_field_blogname', 'theme_options', 'progo_info' );
	add_settings_field( 'progo_blogdescription', 'Slogan', 'progo_field_blogdesc', 'theme_options', 'progo_info' );
	add_settings_field( 'progo_showdesc', 'Show/Hide Slogan', 'progo_field_showdesc', 'theme_options', 'progo_info' );
	add_settings_field( 'progo_support', 'Customer Support', 'progo_field_support', 'theme_options', 'progo_info' );
	add_settings_field( 'progo_copyright', 'Copyright Notice', 'progo_field_copyright', 'theme_options', 'progo_info' );
	
	add_settings_section( 'progo_office', 'Office Information', 'progo_section_text', 'theme_options' );
	add_settings_field( 'progo_businessaddy', 'Business Street Address', 'progo_field_businessaddy', 'theme_options', 'progo_office' );
	add_settings_field( 'progo_businessCSZ', 'Business City, State, Zip', 'progo_field_businessCSZ', 'theme_options', 'progo_office' );
	add_settings_field( 'progo_businessphone', 'Business Phone', 'progo_field_businessphone', 'theme_options', 'progo_office' );
	add_settings_field( 'progo_businessemail', 'Business Email', 'progo_field_businessemail', 'theme_options', 'progo_office' );
	add_settings_field( 'progo_businesshours', 'Business Hours', 'progo_field_businesshours', 'theme_options', 'progo_office' );
	
	add_settings_section( 'progo_homepage', 'Homepage Settings', 'progo_section_text', 'theme_options' );
	add_settings_field( 'progo_layout', 'Page Layout', 'progo_field_layout', 'theme_options', 'progo_homepage' );
	add_settings_field( 'progo_headline', 'Form Headline', 'progo_field_headline', 'theme_options', 'progo_homepage' );
	add_settings_field( 'progo_homeform', 'Form Code', 'progo_field_form', 'theme_options', 'progo_homepage' );
	add_settings_field( 'progo_frontpage', 'Homepage Content Displays', 'progo_field_frontpage', 'theme_options', 'progo_homepage' );
	add_settings_field( 'progo_homeseconds', 'Slide Rotation Speed', 'progo_field_homeseconds', 'theme_options', 'progo_homepage' );

	add_settings_section( 'progo_adv', 'Advanced Options', 'progo_section_text', 'theme_options' );
	add_settings_field( 'progo_field_menuwidth', 'Main Menu Item Width', 'progo_field_menuwidth', 'theme_options', 'progo_adv' );
	add_settings_field( 'progo_footercolor', 'Footer Text Color', 'progo_field_footercolor', 'theme_options', 'progo_adv' );
	add_settings_field( 'progo_field_showtips', 'Show/Hide ProGo Tips', 'progo_field_showtips', 'theme_options', 'progo_adv' );
	add_settings_field( 'progo_field_hidedshare', 'Hide SHARE Widgets', 'progo_field_hidedshare', 'theme_options', 'progo_adv' );
	add_settings_field( 'progo_field_showfbtabs', 'Facebook Tabs', 'progo_field_showfbtabs', 'theme_options', 'progo_adv' );
	add_settings_field( 'progo_field_showppcposts', 'PPC Posts', 'progo_field_showppcposts', 'theme_options', 'progo_adv' );
}
add_action( 'admin_init', 'progo_theme_options_init' );

/**
 * Change the capability required to save the 'progo_options' options group.
 *
 * @see progo_theme_options_init() First parameter to register_setting() is the name of the options group.
 * @see progo_theme_options_add_page() The edit_theme_options capability is used for viewing the page.
 *
 * By default, the options groups for all registered settings require the manage_options capability.
 * This filter is required to change our theme options page to edit_theme_options instead.
 * By default, only administrators have either of these capabilities, but the desire here is
 * to allow for finer-grained control for roles and users.
 *
 * @param string $capability The capability used for the page, which is manage_options by default.
 * @return string The capability to actually use.
 */
function progo_option_page_capability( $capability ) {
	return 'edit_theme_options';
}
add_filter( 'option_page_capability_progo_options', 'progo_option_page_capability' );

if ( ! function_exists( 'progo_colorschemes' ) ):
/**
 * Returns an array of color schemes registered for ProGo Small Business Pro Theme.
 *
 * @since Business Pro 1.3.0
 */
function progo_colorschemes() {
	$color_opts = array(
		'Greyscale',
		'LightBlue',
		'BrownBlue',
		'Wood',
		'GreenWhite',
		'TealOrangeNavy',
		'DarkerBlue'
	);
	
	return apply_filters( 'progo_color_schemes', $color_opts );
}
endif;


if ( ! function_exists( 'progo_field_color' ) ):
/**
 * outputs HTML for "Color Scheme" option on Site Settings page
 * @uses progo_colorschemes() for list of available Color Schemes
 * @since Business Pro 1.0
 */
function progo_field_color() {
	$options = progo_get_theme_options();
	$opts = progo_colorschemes();
	// in case a child theme overwrites the Available Colors progo_colorschemes() function
	if( count($opts) > 0 ) {
	?>
<select id="progo_colorscheme" name="progo_options[colorscheme]" style="float: left; margin-right: 20px; width: 128px;" onchange="updateScreenshot()">
<?php
	foreach ( $opts as $color ) {
		echo '<option value="'. $color .'"'. (($options['colorscheme']==$color) ? ' selected="selected"' : '') .'>'.esc_html($color).'</option>';
	}
?></select><script type="text/javascript">
function updateScreenshot() {
	var color = jQuery('#progo_colorscheme').val();
	jQuery('#progo_color_thm').attr('src','<?php bloginfo('template_url'); ?>/images/colors/'+ color +'/screenshot-thm.jpg');
}

jQuery(function($) {
	$('#progo_colorscheme').after('<img id="progo_color_thm" style="border:1px solid #DFDFDF; width: 150px" />').parent().attr('valign','top');
	updateScreenshot();
});
</script>
<?php } else {
		echo 'COLOR SCHEMES OPTION HAS BEEN OVERWRITTEN';
	}
}
endif;
if ( ! function_exists( 'progo_field_logo' ) ):
/**
 * outputs HTML for custom "Logo" on Site Settings page
 * @since Business Pro 1.0
 */
function progo_field_logo() {
	$options = progo_get_theme_options();
	if ( $options['logo'] != '' ) {
		$upload_dir = wp_upload_dir();
		$dir = trailingslashit( $upload_dir['baseurl'] );
		$imagepath = $dir . $options['logo'];
		echo '<img src="'. esc_attr( $imagepath ) .'" /> [<a href="'. wp_nonce_url("admin.php?progo_admin_action=reset_logo", 'progo_reset_logo') .'">Delete Logo</a>]<br /><span class="description">Replace Logo</span><br />';
	} ?>
<input type="hidden" id="progo_logo" name="progo_options[logo]" value="<?php echo esc_attr( $options['logo'] ); ?>" />
<input type="file" id="progo_logotemp" name="progo_options[logotemp]" />
<span class="description">Upload your logo here.<br />
Maximum dimensions: 598px Width x 75px Height. Larger images will be automatically scaled down to fit size.<br />
Maximum upload file size: <?php echo ini_get( "upload_max_filesize" ); ?>. Allowable formats: gif/jpg/png. Transparent png's / gif's are recommended.</span>
<?php
#needswork
}
endif;
if ( ! function_exists( 'progo_field_layout' ) ):
/**
 * outputs HTML for "Pay Layout" option on Site Settings page
 * @since Business Pro 1.0
 */
function progo_field_layout() {
	$options = progo_get_theme_options();
	$layouton = absint($options['layout']);
	if ( $layouton < 1 || $layouton > 4 ) {
		$layouton = 2;
	}
	$layouts = progo_slide_layouts();
//	echo '<pre style="display:none">'. print_r($options,true) .'</pre>';
	?>
    <table>
    <tr valign="top">
<?php
	foreach ( $layouts as $i => $d ) {
		$chk = $layouton==$i ? ' checked="checked"' : '';
		echo '<td><input type="radio" name="progo_options[layout]" id="progolayout'. $i .'" value="'. $i .'"'. $chk .' /></td><td><label for="progolayout'. $i .'"><img src="'. get_bloginfo('template_url') .'/images/homeslideOptions/'. $i .'.jpg" /><br /><strong>Layout '. $i .'</strong></label><br /><span class="description">'. $d .'</span></td>';
		//<option value="'. $color .'"'. (($options['colorscheme']==$color) ? ' selected="selected"' : '') .'>'.esc_html($color).'</option>';
	}
	?></tr></table>
	<?php
}
endif;
/**
 * outputs HTML for "API Key" field on Site Settings page
 * @since Business Pro 1.0
 */
function progo_field_apikey() {
	$opt = get_option( 'progo_businesspro_apikey', true );
	echo '<input id="apikey" name="progo_options[apikey]" class="regular-text" type="text" value="'. esc_html( $opt ) .'" maxlength="39" />';
	$apiauth = get_option( 'progo_businesspro_apiauth', true );
	switch($apiauth) {
		case 100:
			echo ' <img src="'. get_bloginfo('template_url') .'/images/check.png" alt="aok" class="kcheck" />';
			break;
		default:
			echo ' <img src="'. get_bloginfo('template_url') .'/images/x.gif" alt="X" class="kcheck" title="'. $apiauth .'" />';
			break;
	}
	echo '<br /><span class="description">You API Key was sent via email when you purchased the Business Pro theme from ProGo Themes.</span><br /><br />ProGo Themes are Easy and Quick to Set Up using our Step-by-Step Process.<br />Just follow the ProGo messages above to get your site 100% Ready for Business!';
}
if ( ! function_exists( 'progo_field_blogname' ) ):
/**
 * outputs HTML for "Site Name" field on Site Settings page
 * @since Business Pro 1.0
 */
function progo_field_blogname() {
	$opt = get_option( 'blogname' );
	echo '<input id="blogname" name="progo_options[blogname]" class="regular-text" type="text" value="'. esc_html( $opt ) .'" />';
}
endif;
if ( ! function_exists( 'progo_field_blogdesc' ) ):
/**
 * outputs HTML for "Slogan" field on Site Settings page
 * @since Business Pro 1.0
 */
function progo_field_blogdesc() {
	$opt = get_option( 'blogdescription' ); ?>
<input id="blogdescription" name="progo_options[blogdescription]" class="regular-text" type="text" value="<?php esc_html_e( $opt ); ?>" />
<?php }
endif;
if ( ! function_exists( 'progo_field_showdesc' ) ):
/**
 * outputs HTML for checkbox "Show/Hide Tips" field on Site Settings page
 * @since Business Pro 1.0
 */
function progo_field_showdesc() {
	$options = progo_get_theme_options(); ?>
<fieldset><legend class="screen-reader-text"><span>Show Slogan</span></legend><label for="progo_showdesc">
<input type="checkbox" value="1" id="progo_showdesc" name="progo_options[showdesc]"<?php
	if ( (int) $options['showdesc'] == 1 ) {
		echo ' checked="checked"';
	} ?> />
Show the Site Slogan next to the Logo at the top of <a target="_blank" href="<?php echo esc_url( trailingslashit( get_bloginfo( 'url' ) ) ); ?>">your site</a></label>
</fieldset>
<?php }
endif;
if ( ! function_exists( 'progo_field_showtips' ) ):
/**
 * outputs HTML for checkbox "Show Slogan" field on Site Settings page
 * @since Business Pro 1.0
 */
function progo_field_showtips() {
	$options = progo_get_theme_options(); ?>
<label for="progo_showtips">
<input type="checkbox" value="1" id="progo_showtips" name="progo_options[showtips]"<?php
	if ( (int) $options['showtips'] == 1 ) {
		echo ' checked="checked"';
	} ?> />
Show ProGo Tips <img src="<?php bloginfo('template_url'); ?>/images/tip.png" alt="Tip" /> for Admin users viewing the front-end of <a target="_blank" href="<?php echo esc_url( trailingslashit( get_bloginfo( 'url' ) ) ); ?>">your site</a></label>
<?php }
endif;
if ( ! function_exists( 'progo_field_hidedshare' ) ):
/**
 * outputs HTML for checkbox "Show Slogan" field on Site Settings page
 * @since Business Pro 1.0
 */
function progo_field_hidedshare() {
	$options = progo_get_theme_options(); ?>
<label for="progo_hidedshare">
<input type="checkbox" value="1" id="progo_hidedshare" name="progo_options[hidedshare]"<?php
	if ( (int) $options['hidedshare'] == 1 ) {
		echo ' checked="checked"';
	} ?> />
Hide the default "SHARE" widget from Page sidebars.</label> <span class="description">(default widgets are also automatically replaced by any other <a href=
"widgets.php">Widgets</a> placed in sidebars)</span>
<?php }
endif;
if ( ! function_exists( 'progo_field_showfbtabs' ) ):
/**
 * outputs HTML for checkbox "Show Slogan" field on Site Settings page
 * @since Business Pro 1.0
 */
function progo_field_showfbtabs() {
	$options = progo_get_theme_options(); ?>
<label for="progo_showfbtabs">
<input type="checkbox" value="1" id="progo_showfbtabs" name="progo_options[fbtabs]"<?php
	if ( (int) $options['fbtabs'] == 1 ) {
		echo ' checked="checked"';
	} ?> />
Enable "Facebook Tabs" custom post type &amp; templates.</label> <span class="description">(some assembly required)</span>
<?php }
endif;
if ( ! function_exists( 'progo_field_showppcposts' ) ):
/**
 * outputs HTML for checkbox "Show Slogan" field on Site Settings page
 * @since Business Pro 1.0
 */
function progo_field_showppcposts() {
	$options = progo_get_theme_options(); ?>
<label for="progo_showppcposts">
<input type="checkbox" value="1" id="progo_showppcposts" name="progo_options[ppcposts]"<?php
	if ( (int) $options['ppcposts'] == 1 ) {
		echo ' checked="checked"';
	} ?> />
Enable "PPC Posts" custom post type &amp; templates.</label> <span class="description">(some assembly required)</span>
<?php }
endif;
if ( ! function_exists( 'progo_field_support' ) ):
/**
 * outputs HTML for "Customer Support" field on Site Settings page
 * @since Business Pro 1.0
 */
function progo_field_support() {
	$options = progo_get_theme_options();
	?>
<input id="progo_support" name="progo_options[support]" value="<?php esc_html_e( $options['support'] ); ?>" class="regular-text" type="text" />
<span class="description">Enter either a Phone # (like <em>222-333-4444</em>) or email address</span>
<?php }
endif;
if ( ! function_exists( 'progo_field_copyright' ) ):
/**
 * outputs HTML for "Copyright Notice" field on Site Settings page
 * @since Business Pro 1.0
 */
function progo_field_copyright() {
	$options = progo_get_theme_options();
	?>
<input id="progo_copyright" name="progo_options[copyright]" value="<?php esc_html_e( $options['copyright'] ); ?>" class="regular-text" type="text" />
<span class="description">Copyright notice that appears on the right side of your site's footer.</span>
<?php }
endif;
if ( ! function_exists( 'progo_field_businessaddy' ) ):
/**
 * outputs HTML for "Business Address" field on Site Settings page
 * @since SmallBusiness 1.0
 */
function progo_field_businessaddy() {
	$options = progo_get_theme_options();
	?>
<input id="progo_businessaddy" name="progo_options[businessaddy]" class="regular-text" type="text" value="<?php esc_html_e( $options['businessaddy'] ); ?>" />
<span class="description">This address will appear in the Office Info widget.</span>
<?php }
endif;
if ( ! function_exists( 'progo_field_businessCSZ' ) ):
/**
 * outputs HTML for "Business City, State, Zip" field on Site Settings page
 * @since SmallBusiness 1.0
 */
function progo_field_businessCSZ() {
	$options = progo_get_theme_options();
	?>
<input id="progo_businessCSZ" name="progo_options[businessCSZ]" class="regular-text" type="text" value="<?php esc_html_e( $options['businessCSZ'] ); ?>" />
<span class="description">This address will appear in the Office Info widget under the street address above.</span>
<?php }
endif;
if ( ! function_exists( 'progo_field_businessphone' ) ):
/**
 * outputs HTML for "Business Phone" field on Site Settings page
 * @since SmallBusiness 1.0
 */
function progo_field_businessphone() {
	$options = progo_get_theme_options();
	?>
<input id="progo_businessphone" name="progo_options[businessphone]" class="regular-text" type="text" value="<?php esc_html_e( $options['businessphone'] ); ?>" />
<span class="description">This phone will appear in the Office Info widget.</span>
<?php }
endif;
if ( ! function_exists( 'progo_field_businessemail' ) ):
/**
 * outputs HTML for "Business Email" field on Site Settings page
 * @since SmallBusiness 1.0
 */
function progo_field_businessemail() {
	$options = progo_get_theme_options();
	?>
<input id="progo_businessemail" name="progo_options[businessemail]" class="regular-text" type="text" value="<?php esc_html_e( $options['businessemail'] ); ?>" />
<span class="description">This email address will appear in the Office Info widget.</span>
<?php }
endif;
if ( ! function_exists( 'progo_field_businesshours' ) ):
/**
 * outputs HTML for "Business Hours" field on Site Settings page
 * @since SmallBusiness 1.0
 */
function progo_field_businesshours() {
$options = progo_get_theme_options(); ?>
<input id="progo_businesshours" name="progo_options[businesshours]" class="regular-text" type="text" value="<?php esc_html_e( $options['businesshours'] ); ?>" />
<span class="description">These hours will appear in the Office Info widget.</span>
<?php }
endif;
if ( ! function_exists( 'progo_field_footercolor' ) ):
/**
 * outputs HTML for "Footer Text Color" field on Site Settings page
 * @since Business Pro 1.0
 */
function progo_field_footercolor() {
	$options = progo_get_theme_options();
	?>
<fieldset><legend class="screen-reader-text"><span><?php _e( 'Background Color' ); ?></span></legend>
<?php $show_clear = ( $options['footercolor'] != '' ) ? '' : ' style="display:none"'; ?>
<input type="text" name="progo_options[footercolor]" id="background-color" value="<?php echo esc_attr( $options['footercolor'] ) ?>" />
<a class="hide-if-no-js" href="#" id="pickcolor"><?php _e('Select a Color'); ?></a> <span <?php echo $show_clear; ?>class="hide-if-no-js" id="clearcolor"> (<a href="#"><?php _e( 'Clear' ); ?></a>)</span>
<div id="colorPickerDiv" style="z-index: 100; background:#eee; border:1px solid #ccc; position:absolute; display:none;"></div>
</fieldset>
<?php }
endif;
if ( ! function_exists( 'progo_field_headline' ) ):
/**
 * outputs HTML for "Customer Support" field on Site Settings page
 * @since Business Pro 1.0
 */
function progo_field_headline() {
	$options = progo_get_theme_options();
	if ( $options['headline'] != 'Get Your Customers|What They Need Most!' ) {
		// just double check...
		$options['headline'] = str_replace( "\n", '|', $options['headline'] );
	}
	?>
    <input id="progo_headline" name="progo_options[headline]" class="regular-text" type="text" value="<?php esc_html_e( $options['headline'] ); ?>" />
    <span class="description">Headline Call-to-Action in the Arrow above your Form. Use | to create a line break in the text.</span>
<?php }
endif;
if ( ! function_exists( 'progo_field_form' ) ):
/**
 * outputs HTML for "Customer Support" field on Site Settings page
 * @since Business Pro 1.0
 */
function progo_field_form() {
	$options = progo_get_theme_options();
	?>
<textarea id="progo_homeform" name="progo_options[form]" rows="3" cols="100%"><?php esc_attr_e( $options['form'] ); ?></textarea>
<?php }
endif;
if ( ! function_exists( 'progo_field_frontpage' ) ):
/**
 * outputs HTML for Homepage "Displays" field on Site Settings page
 * @since Business Pro 1.0
 */
function progo_field_frontpage() {
	// Latest Blog Posts, (Featured Products), Static Content
	$choices = array(
		'posts' => 'Latest Blog Posts',
		'page' => 'Static Content'
	);
	$msgs = array(
		'posts' => '<a href="edit.php" target="_blank">Edit Posts Here</a>',
		'page' => '<a href="post.php?post='. get_option('progo_homepage_id') .'&action=edit" target="_blank">Edit Homepage Content Here</a>'
	);
	$msg = '';
	
//	$msg .= '<pre>'. print_r(get_option('show_on_front'),true)  .'</pre>'. print_r(get_option('page_on_front'),true) .'</pre>'. print_r(get_option('page_for_posts'),true) .'</pre>';
	
	$options = progo_get_theme_options();
	// check just in case show_on_front changed since this was last updated?
	// $options['frontpage'] = get_option('show_on_front');
	
	?><p><select id="progo_frontpage" name="progo_options[frontpage]" onchange="progo_frontpage_msg();"><?php
    foreach ( $choices as $k => $c ) {
		echo '<option value="'. $k .'"';
		if( $k == $options['frontpage'] ) {
			echo ' selected="selected"';
		}
		echo '>'. esc_attr($c) .'</option>';
	}
    ?></select><span class="description"><?php echo ( $msg != '' ? $msg : $msgs[$options['frontpage']] ); ?></span></p>
<script type="text/javascript">
function progo_frontpage_msg() {
	var msg = '';
	var sel = jQuery('#progo_frontpage');
	switch( sel.val() ) { <?php
	foreach ( $msgs as $k => $v ) {
		echo "case '$k':\n";
			echo "msg = '$v';\n";
			echo "break;";
	} ?>
	}
	sel.next().html(msg);
}
</script>
<?php }
endif;
if ( ! function_exists( 'progo_field_menuwidth' ) ):
/**
 * outputs HTML for Adv Option "Main Menu Width" field on Site Settings page
 * @since Business Pro 1.2.6
 */
function progo_field_menuwidth() {
	// Latest Blog Posts, (Featured Products), Static Content
	$choices = array(
		'fixed' => 'Fixed Width',
		'auto' => 'Auto'
	);
	
	$options = progo_get_theme_options();
	// check just in case show_on_front changed since this was last updated?
	// $options['frontpage'] = get_option('show_on_front');
	
	?><select id="progo_menuwidth" name="progo_options[menuwidth]"><?php
    foreach ( $choices as $k => $c ) {
		echo '<option value="'. $k .'"';
		if( $k == $options['menuwidth'] ) {
			echo ' selected="selected"';
		}
		echo '>'. esc_attr($c) .'</option>';
	}
    ?></select><span class="description">Would you like the top links in the Main Menu on your site to be Fixed Width or Auto Width?</span>
<?php }
endif;
if ( ! function_exists( 'progo_field_homeseconds' ) ):
/**
 * outputs HTML for Homepage "Cycle Seconds" field on Site Settings page
 * @since Business Pro 1.0
 */
function progo_field_homeseconds() {
	$options = progo_get_theme_options();
	// check just in case show_on_front changed since this was last updated?
	// $options['frontpage'] = get_option('show_on_front');
	?><p><input id="progo_homeseconds" name="progo_options[homeseconds]" type="text" size="2" value="<?php echo absint($options['homeseconds']); ?>"><span class="description"> sec. per slide. Enter "0" to disable auto-rotation.</span></p>
<?php }
endif;
if ( ! function_exists( 'progo_section_text' ) ):
/**
 * (dummy) function called by 
 * add_settings_section( [id] , [title], 'progo_section_text', 'progo_site_settings' );
 * echos anchor link for that section
 * @since Business Pro 1.0
 */
function progo_section_text( $args ) {
	echo '<a name="'. $args['id'] .'"></a>';
}
endif;

/**
 * Returns the default options for Twenty Eleven.
 *
 * @since Business Pro 1.3.0
 */
function progo_get_default_theme_options() {
	$default_theme_options = array(
		// THEME CUSTOMIZATION
		"colorscheme" => "Greyscale",
		"logo" => "",
		// GENERAL SITE INFORMATION
		"blogname" => get_option( 'blogname' ),
		"blogdescription" => get_option( 'blogdescription' ),
		"showdesc" => 1,
		"support" => "(858) 555-1234",
		"copyright" => "© Copyright ". date('Y') .", All Rights Reserved",
		// OFFICE INFORMATION
		"businessaddy" => "",
		"businessCSZ" => "",
		"businessphone" => "",
		"businessemail" => "",
		"businesshours" => "",
		// HOMEPAGE SETTINGS
		"layout" => 2,
		"headline" => "Get Your Customers|What They Need Most!",
		"form" => "",
		"frontpage" => get_option( 'show_on_front' ),
		"homeseconds" => 6,
		// ADVANCED OPTIONS
		"menuwidth" => "fixed",
		"footercolor" => "",
		"showtips" => 1,
		"hidedshare" => 0,
		"fbtabs" => 0,
		"ppcposts" => 0,
	);

	return apply_filters( 'progo_default_theme_options', $default_theme_options );
}

/**
 * Helper function returns the theme options array
 *
 * @since Business Pro 1.3.0
 */
function progo_get_theme_options() {
	return get_option( 'progo_options', progo_get_default_theme_options() );
}

/**
 * Outputs Theme Options page.
 *
 * @since Business Pro 1.3.0
 */
function progo_theme_options_render_page() {
	?>
	<div class="wrap">
		<?php screen_icon(); ?>
		<?php $theme_name = function_exists( 'wp_get_theme' ) ? wp_get_theme() : get_current_theme(); ?>
		<h2><?php printf( __( 'ProGo %s Theme Options', 'progo' ), $theme_name ); ?></h2>
		<?php settings_errors(); ?>

		<form action="options.php" enctype="multipart/form-data" method="post">
			<?php
				settings_fields( 'progo_options' );
				do_settings_sections( 'progo_api' );
				?>
        <p class="submit"><input type="submit" value="Save Changes" class="button-primary" /></p>
                <?php
				do_settings_sections( 'theme_options' );
		?>
        <p class="submit"><input type="submit" value="Save Changes" class="button-primary" /></p>
		<p><br /></p>
		</form>
        <h3>Additional ProGo Theme Options</h3>
        <table class="form-table">
        <?php
		$addl = array(
			'Homepage Slides' => array(
				'url' => 'edit.php?post_type=progo_homeslide',
				'btn' => 'Manage Homepage Slides',
				'desc' => 'Edit existing slides, change text, upload images, and add more slides.'
			),
			'Background' => array(
				'url' => 'themes.php?page=custom-background',
				'btn' => 'Customize Your Background',
				'desc' => 'Change the underlying color, or upload your own custom background image.'
			),
			'Widgets' => array(
				'url' => 'widgets.php',
				'btn' => 'Manage Widgets',
				'desc' => 'Customize what appears in the right column on various areas of your site.'
			),
			'Menus' => array(
				'url' => 'nav-menus.php',
				'btn' => 'Manage Menu Links',
				'desc' => 'Control the links in the Header &amp; Footer area of your site.'
			),
			'Contact Forms' => array(
				'url' => 'admin.php?contactform=1&page=wpcf7',
				'btn' => 'Manage Contact Forms',
				'desc' => 'Edit Contact Form 7 Forms that appear on your site, like on the Homepage.'
			)
		);
		if ( is_plugin_active( 'contact-form-7/wp-contact-form-7.php' ) === false ) {
			unset($addl['Contact Forms']);
		}
		foreach ( $addl as $k => $v ) {
			echo '<tr><th scope="row">'. wp_kses($k,array()) .'</th><td><a href="'. esc_url($v['url']) .'" class="button" target="_blank">'. wp_kses($v['btn'],array()) .' &raquo;</a> <span class="description">'. wp_kses($v['desc'],array()) .'</span></td></tr>';
		} ?>
        </table><p><br /></p>
        <h3><a name="recommended"></a>Recommended Plugins</h3>
		<?php if ( function_exists( 'alex_recommends_widget' ) ) {
            alex_recommends_widget();
        } else { ?>
            <p>The following plugins can help improve various aspects of your WordPress + ProGo Themes site:</p>
            <ul style="list-style:outside; padding: 0 1em">
            <?php
            $pRec = array();
            $pRec[] = array('name'=>'WordPress SEO by Yoast','stub'=>'wordpress-seo','desc'=>'Out-of-the-box SEO. Easily control your keywords, meta descriptions, and more');
            $pRec[] = array('name'=>'ShareThis','stub'=>'share-this','desc'=>'Get more exposure for your site as visitors share it with their friends');
            $pRec[] = array('name'=>'Google Analytics for WordPress','stub'=>'google-analytics-for-wordpress','desc'=>'Add Google Analytics to your site, with options to track external links, mailto\'s, and downloads');
            $pRec[] = array('name'=>'NextGEN Gallery','stub'=>'nextgen-gallery','desc'=>'A fully integrated Image Gallery plugin with dozens of options and features');
            $pRec[] = array('name'=>'Contact Form 7 to Database Extension','stub'=>'contact-form-7-to-database-extension','desc'=>'Saves submitted form data from Contact Form 7 to your database, for future reference');
            $pRec[] = array('name'=>'Duplicate Post','stub'=>'duplicate-post','desc'=>'Add functionality to Save Page As...');
            $pRec[] = array('name'=>'WB DB Backup','stub'=>'wp-db-backup','desc'=>'On-demand backup of your WordPress database');
            
            foreach( $pRec as $plug ) {
                echo '<li>';
                echo '<a title="Learn more &amp; install '. esc_attr( $plug['name'] ) .'" class="thickbox" href="'. get_bloginfo('url') .'/wp-admin/plugin-install.php?tab=plugin-information&amp;plugin='. $plug['stub'] .'&amp;TB_iframe=true&amp;width=640&amp;height=560">';
                echo esc_html($plug['name']) .'</a> : '. esc_html($plug['desc']) .'</li>';
            }
            ?>
            <li><a href="http://www.gravityforms.com/" target="_blank">Gravity Forms</a> : when Contact Form 7 just isn't cutting it. Gravity Forms is a super robust Forms plugin, with Drag and Drop form creation, and so much more</li>
            </ul>
    <?php } ?>
            <p><br /></p>
    <div class="clear"></div>
    </div>
	<?php
}

if ( ! function_exists( 'progo_validate_options' ) ):
/**
 * ProGo Site Settings Options validation function
 * from register_setting( 'progo_options', 'progo_options', 'progo_validate_options' );
 * in progo_admin_init()
 * also handles uploading of custom Site Logo
 * @param $input options to validate
 * @return $input after validation has taken place
 * @since Business Pro 1.0
 */
function progo_validate_options( $input ) {
	$output = $defaults = progo_get_default_theme_options();
	
	if( isset($input['apikey']) ) {
		$input['apikey'] = wp_kses( $input['apikey'], array() );
		// store API KEY in its own option
		if ( $input['apikey'] != get_option( 'progo_businesspro_apikey' ) ) {
			update_option( 'progo_businesspro_apikey', substr( $input['apikey'], 0, 39 ) );
		}
	}
	
	// do validation here...
	$arr = array( 'blogname', 'blogdescription', 'colorscheme', 'support', 'copyright', 'footercolor', 'headline' );
	foreach ( $arr as $opt ) {
		$input[$opt] = wp_kses( $input[$opt], array() );
	}
	
	// opt[colorscheme] must be one of the allowed colors
	$colors = progo_colorschemes();
	if ( ! in_array( $input['colorscheme'], $colors ) ) {
		$input['colorscheme'] = 'Greyscale';
	}
	
	
	if ( isset( $input['footercolor'] ) && preg_match( '/^#?([a-f0-9]{3}){1,2}$/i', $input['footercolor'] ) ) {
		$input['footercolor'] = '#' . strtolower( ltrim( $input['footercolor'], '#' ) );
	} else {
		$input['footercolor'] = progo_get_default_footer_color();
	}
	
	$choices = array(
		'posts',
		'page',
	);
	if ( ! in_array( $input['frontpage'], $choices ) ) {
		$input['frontpage'] = get_option('show_on_front');
	}
	switch ( $input['frontpage'] ) {
		case 'posts':
			update_option( 'show_on_front', 'posts' );
			break;
		case 'page':
			update_option( 'show_on_front', 'page' );
			update_option( 'page_on_front', get_option('progo_homepage_id') );
			break;
	}
	$choices = array(
		'fixed',
		'auto',
	);
	if ( ! in_array( $input['menuwidth'], $choices ) ) {
		$input['frontpage'] = 'fixed';
	}
	// opt[showdesc] can only be 1 or 0
	$bincheck = array( 'showdesc', 'showtips', 'hidedshare', 'fbtabs', 'ppcposts' );
	foreach( $bincheck as $f ) {
		if ( (int) $input[$f] != 1 ) {
			$input[$f] = 0;
		}
	}
	
	// opt[layout] can only be an int  1 <= int <= 4
	$intcheck = absint( $input['layout'] );
	if ( $intcheck < 1 || $intcheck > 4 ) {
		$intcheck = 1;
	}
	$input['layout'] = absint( $intcheck );
	
	// save blogname & blogdescription to other options as well
	$arr = array( 'blogname', 'blogdescription' );
	foreach ( $arr as $opt ) {
		if ( $input[$opt] != get_option( $opt ) ) {
			update_option( $opt, $input[$opt] );
		}
	}
	
	// check SUPPORT field & set option['support_email'] flag if we have an email
	$input['support_email'] = is_email( $input['support'] );
	
		// upload error?
		$error = '';
	// upload the file - BASED OFF WP USERPHOTO PLUGIN
	if ( isset($_FILES['progo_options']) && @$_FILES['progo_options']['name']['logotemp'] ) {
		if ( $_FILES['progo_options']['error']['logotemp'] ) {
			switch ( $_FILES['progo_options']['error']['logotemp'] ) {
				case UPLOAD_ERR_INI_SIZE:
				case UPLOAD_ERR_FORM_SIZE:
					$error = "The uploaded file exceeds the max upload size.";
					break;
				case UPLOAD_ERR_PARTIAL:
					$error = "The uploaded file was only partially uploaded.";
					break;
				case UPLOAD_ERR_NO_FILE:
					$error = "No file was uploaded.";
					break;
				case UPLOAD_ERR_NO_TMP_DIR:
					$error = "Missing a temporary folder.";
					break;
				case UPLOAD_ERR_CANT_WRITE:
					$error = "Failed to write file to disk.";
					break;
				case UPLOAD_ERR_EXTENSION:
					$error = "File upload stopped by extension.";
					break;
				default:
					$error = "File upload failed due to unknown error.";
			}
		} elseif ( ! $_FILES['progo_options']['size']['logotemp'] ) {
			$error = "The file &ldquo;". $_FILES['progo_options']['name']['logotemp'] ."&rdquo; was not uploaded. Did you provide the correct filename?";
		} elseif ( ! in_array( $_FILES['progo_options']['type']['logotemp'], array( "image/jpeg", "image/pjpeg", "image/gif", "image/png", "image/x-png" ) ) ) {
			$error = "The uploaded file type &ldquo;". $_FILES['progo_options']['type']['logotemp'] ."&rdquo; is not allowed.";
		}
		$tmppath = $_FILES['progo_options']['tmp_name']['logotemp'];
		
		$imageinfo = null;
		if ( ! $error ) {			
			$imageinfo = getimagesize($tmppath);
			if ( ( ! $imageinfo ) || ( ! $imageinfo[0] ) || ( ! $imageinfo[1] ) ) {
				$error = __("Unable to get image dimensions.", 'user-photo');
			} else if ( $imageinfo[0] > 598 || $imageinfo[1] > 75 ) {
				/*
				if(userphoto_resize_image($tmppath, null, $userphoto_maximum_dimension, $error)) {
					$imageinfo = getimagesize($tmppath);
				}
				*/
				$filename = $tmppath;
				$newFilename = $filename;
				$jpeg_compression = 86;
				#if(empty($userphoto_jpeg_compression))
				#	$userphoto_jpeg_compression = USERPHOTO_DEFAULT_JPEG_COMPRESSION;
				
				$info = @getimagesize($filename);
				if ( ( ! $info ) || ( ! $info[0] ) || ( ! $info[1] ) ) {
					$error = __("Unable to get image dimensions.", 'user-photo');
				}
				//From WordPress image.php line 22
				else if (
					! function_exists( 'imagegif' ) && $info[2] == IMAGETYPE_GIF
					||
					! function_exists( 'imagejpeg' ) && $info[2] == IMAGETYPE_JPEG
					||
					! function_exists( 'imagepng' ) && $info[2] == IMAGETYPE_PNG
				) {
					$error = __( 'Filetype not supported.', 'user-photo' );
				}
				else {
					// create the initial copy from the original file
					if ( $info[2] == IMAGETYPE_GIF ) {
						$image = imagecreatefromgif( $filename );
					}
					elseif ( $info[2] == IMAGETYPE_JPEG ) {
						$image = imagecreatefromjpeg( $filename );
					}
					elseif ( $info[2] == IMAGETYPE_PNG ) {
						$image = imagecreatefrompng( $filename );
					}
					if ( ! isset( $image ) ) {
						$error = __("Unrecognized image format.", 'user-photo');
						return false;
					}
					if ( function_exists( 'imageantialias' ))
						imageantialias( $image, TRUE );
			
					// make sure logo is within max 598 x 75 dimensions
					
					// figure out the longest side
					if ( ( $info[0] / $info[1] ) > 8 ) { // resize width to fit 
						$image_width = $info[0];
						$image_height = $info[1];
						$image_new_width = 598;
			
						$image_ratio = $image_width / $image_new_width;
						$image_new_height = round( $image_height / $image_ratio );
					} else { // resize height to fit
						$image_width = $info[0];
						$image_height = $info[1];
						$image_new_height = 75;
			
						$image_ratio = $image_height / $image_new_height;
						$image_new_width = round( $image_width / $image_ratio );
					}
			
					$imageresized = imagecreatetruecolor( $image_new_width, $image_new_height);
					@ imagecopyresampled( $imageresized, $image, 0, 0, 0, 0, $image_new_width, $image_new_height, $info[0], $info[1] );
			
					// move the thumbnail to its final destination
					if ( $info[2] == IMAGETYPE_GIF ) {
						if ( ! imagegif( $imageresized, $newFilename ) ) {
							$error = __( "Logo path invalid" );
						}
					}
					elseif ( $info[2] == IMAGETYPE_JPEG ) {
						if ( ! imagejpeg( $imageresized, $newFilename, $jpeg_compression ) ) {
							$error = __( "Logo path invalid" );
						}
					}
					elseif ( $info[2] == IMAGETYPE_PNG ) {
						@ imageantialias($imageresized,true);
						@ imagealphablending($imageresized, false);
						@ imagesavealpha($imageresized,true);
						$transparent = imagecolorallocatealpha($imageresized, 255, 255, 255, 0);
						for($x=0;$x<$image_new_width;$x++) {
							for($y=0;$y<$image_new_height;$y++) {
							@ imagesetpixel( $imageresized, $x, $y, $transparent );
							}
						}
						@ imagecopyresampled( $imageresized, $image, 0, 0, 0, 0, $image_new_width, $image_new_height, $info[0], $info[1] );

						if ( ! imagepng( $imageresized, $newFilename ) ) {
							$error = __( "Logo path invalid" );
						}
					}
				}
				if(empty($error)) {
					$imageinfo = getimagesize($tmppath);
				}
			}
		}
		
		if ( ! $error ) {
			$upload_dir = wp_upload_dir();
			$dir = trailingslashit( $upload_dir['basedir'] );
			$imagepath = $dir . $_FILES['progo_options']['name']['logotemp'];
			
			if ( ! move_uploaded_file( $tmppath, $imagepath ) ) {
				$error = "Unable to place the user photo at: ". $imagepath;
			}
			else {
				chmod($imagepath, 0666);
				
				$input['logo'] = $_FILES['progo_options']['name']['logotemp'];
	
				/*
				if($oldFile && $oldFile != $newFile)
					@unlink($dir . '/' . $oldFile);
				*/
			}
		}
	}
	update_option('progo_settings_just_saved',1);
	
	return $input;
}
endif;
if ( ! function_exists( 'progo_get_default_footer_color' ) ):
/**
 * Returns the default color for text & links in the footer area, based on color scheme.
 *
 * @since Business Pro 1.4
 *
 * @param $string $footercolor current footer color? Defaults for the active color scheme.
 * @return $string Color.
*/
function progo_get_default_footer_color( $footercolor = '' ) {
	if ( $footercolor == '' ) {
		$options = progo_get_theme_options();
		$color_scheme = $options['colorscheme'];
		
		switch ( $color_scheme ) {
			// more handling here ?
			case 'BlackOrange':
			case 'DarkGreen':
			case 'GreenBrown':
				$footercolor = '#FFFFFF';
				break;
			default:
				$footercolor = '#545454';
				break;
		}
	}
	$footercolor = apply_filters( 'progo_default_footercolor', $footercolor );
	return $footercolor;
}
endif;
if ( ! function_exists( 'progo_get_default_custom_bg' ) ):
/**
 * Returns the default background image, based on color scheme.
 *
 * @since Business Pro 1.4
 *
 * @return $string URL of the img src.
*/
function progo_get_default_custom_bg() {
	$options = progo_get_theme_options();
	$color_scheme = $options['colorscheme'];
	
	switch ( $color_scheme ) {
		default:
			$defimg = get_template_directory_uri() . '/images/colors/'. $color_scheme .'/bg.jpg';
			break;
	}
	$defimg = apply_filters( 'progo_default_bg', $defimg );
	return $defimg;
}
endif;
if ( ! function_exists( 'progo_customize_register' ) ):
/**
 * Implements theme options into Theme Customizer
 *
 * @param $wp_customize Theme Customizer object
 * @return void
 *
 * @since Business Pro 1.4.0
 */
function progo_customize_register( $wp_customize ) {
	$wp_customize->get_setting( 'blogname' )->transport = 'postMessage';
	$wp_customize->get_setting( 'blogdescription' )->transport = 'postMessage';

	$options  = progo_get_theme_options();
	$defaults = progo_get_default_theme_options();

	$wp_customize->add_setting( 'progo_options[colorscheme]', array(
		'default'    => $defaults['colorscheme'],
		'type'       => 'option',
		'capability' => 'edit_theme_options',
	) );

	$schemes = progo_colorschemes();
	$choices = array();
	foreach ( $schemes as $scheme ) {
		$choices[ $scheme ] = $scheme;
	}

	$wp_customize->add_control( 'progo_colorscheme', array(
		'label'    => __( 'Color Scheme', 'progo' ),
		'section'  => 'colors',
		'settings' => 'progo_options[colorscheme]',
		'type'     => 'select',
		'choices'  => $choices,
		'priority' => 5,
	) );
	
	$wp_customize->add_setting( 'progo_options[footercolor]', array(
		'default'           => progo_get_default_footer_color( $options['footercolor'] ),
		'type'              => 'option',
		'sanitize_callback' => 'sanitize_hex_color',
		'capability'        => 'edit_theme_options',
	) );
	
	$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'footercolor', array(
		'label'    => __( 'Footer Text Color', 'progo' ),
		'section'  => 'colors',
		'settings' => 'progo_options[footercolor]',
	) ) );
	
	// show/hide slogan
	$wp_customize->add_setting( 'progo_options[showdesc]', array(
		'default'    => $defaults['showdesc'],
		'type'       => 'option',
		'capability' => 'edit_theme_options',
	) );
	
	$wp_customize->add_control( 'progo_showdesc', array(
		'label'    => __( 'Show/Hide Slogan', 'progo' ),
		'section'  => 'title_tagline',
		'settings' => 'progo_options[showdesc]',
		'type'     => 'checkbox',
		'transport' => 'postMessage',
		'priority' => 50,
	) );
	
	
	$wp_customize->add_setting( 'progo_options[menuwidth]', array(
		'default'    => $defaults['showdesc'],
		'type'       => 'option',
		'capability' => 'edit_theme_options',
	) );
	
	$choices = array(
		'fixed' => 'Fixed Width',
		'auto' => 'Auto'
	);
	$wp_customize->add_control( 'progo_menuwidth', array(
		'label'    => __( 'Main Menu Item Width', 'progo' ),
		'section'  => 'nav',
		'settings' => 'progo_options[menuwidth]',
		'type'     => 'radio',
		'choices'  => $choices,
		'priority' => 1,
	) );
	
	// General Site Info
	$wp_customize->add_section( 'progo_info', array(
		'title'    => __( 'Site Information', 'progo' ),
		'priority' => 50,
	) );
	
	$wp_customize->add_setting( 'progo_options[support]', array(
		'default'    => $defaults['support'],
		'type'       => 'option',
		'capability' => 'edit_theme_options',
	) );
	
	$wp_customize->add_control( 'progo_support', array(
		'label'    => __( 'Customer Support', 'progo' ),
		'section'  => 'progo_info',
		'settings' => 'progo_options[support]',
		'type'     => 'text',
		'priority' => 1,
	) );
	
	$wp_customize->add_setting( 'progo_options[copyright]', array(
		'default'    => $defaults['copyright'],
		'type'       => 'option',
		'capability' => 'edit_theme_options',
	) );
	
	$wp_customize->add_control( 'progo_copyright', array(
		'label'    => __( 'Copyright Notice', 'progo' ),
		'section'  => 'progo_info',
		'settings' => 'progo_options[copyright]',
		'type'     => 'text',
		'priority' => 2,
	) );
		
	// Default Layout
	$wp_customize->add_section( 'progo_homepage', array(
		'title'    => __( 'Homepage Settings', 'progo' ),
		'priority' => 50,
	) );

	$wp_customize->add_setting( 'progo_options[layout]', array(
		'type'              => 'option',
		'default'           => $defaults['layout'],
		'sanitize_callback' => 'sanitize_key',
	) );

	$choices = progo_slide_layouts();

	$wp_customize->add_control( 'progo_options[layout]', array(
		'label'    => __( 'Page Layout', 'progo' ),
		'section'    => 'progo_homepage',
		'type'       => 'radio',
		'choices'    => $choices,
		'priority' => 1,
	) );
	
	$wp_customize->add_setting( 'progo_options[headline]', array(
		'default'    => $defaults['headline'],
		'type'       => 'option',
		'capability' => 'edit_theme_options',
		'transport' => 'postMessage',
	) );
	
	$wp_customize->add_control( 'progo_headline', array(
		'label'    => __( 'Form Headline', 'progo' ),
		'section'  => 'progo_homepage',
		'settings' => 'progo_options[headline]',
		'type'     => 'text',
		'priority' => 2,
	) );
	
	$wp_customize->add_setting( 'progo_options[homeseconds]', array(
		'default'    => $defaults['homeseconds'],
		'type'       => 'option',
		'capability' => 'edit_theme_options',
	) );
	
	$wp_customize->add_control( 'progo_homeseconds', array(
		'label'    => __( 'Slide Roation Speed', 'progo' ),
		'section'  => 'progo_homepage',
		'settings' => 'progo_options[homeseconds]',
		'type'     => 'text',
		'priority' => 2,
	) );
}
endif;
add_action( 'customize_register', 'progo_customize_register' );

if ( ! function_exists( 'progo_customize_preview_js' ) ):
/**
 * Bind JS handlers to make Theme Customizer preview reload changes asynchronously.
 * Used with blogname and blogdescription.
 *
 * @since Business Pro 1.4.0
 */
function progo_customize_preview_js() {
	wp_enqueue_script( 'progo-customizer', get_template_directory_uri() . '/inc/theme-customizer.js', array( 'customize-preview' ), '20120523', true );
}
endif;
add_action( 'customize_preview_init', 'progo_customize_preview_js' );

if ( ! function_exists( 'progo_custom_background_cb' ) ):
/**
 * overwrite default custom bg callback
 *
 * @since Business Pro 1.4.0
 */
function progo_custom_background_cb() {
	// $background is the saved custom image, or the default image.
	$background = get_background_image();

	// $color is the saved custom color.
	// A default has to be specified in style.css. It will not be printed here.
	$color = get_theme_mod( 'background_color' );

	if ( ! $background && ! $color )
		return;
	
	// and then...
	$options = progo_get_theme_options();
	
	if ( $color ) {
		$style = $color ? "background-color: #$color;" : '';
	} else {
		$style = '';
		
		if ( $options['colorscheme'] == 'BrownBlue' ) {
			$style = "background-color: #CCDEE3;";
		}
	}
	if ( $background ) {
		$image = " background-image: url('$background');";

		$default_repeat = 'repeat';
		if ( $options['colorscheme'] == 'BrownBlue' ) {
			$default_repeat = 'repeat-x';
		}
		
		$repeat = get_theme_mod( 'background_repeat', 'repeat' );
		if ( ! in_array( $repeat, array( 'no-repeat', 'repeat-x', 'repeat-y', 'repeat' ) ) )
			$repeat = 'repeat';
		$repeat = " background-repeat: $repeat;";

		$position = get_theme_mod( 'background_position_x', 'left' );
		if ( ! in_array( $position, array( 'center', 'right', 'left' ) ) )
			$position = 'left';
		$position = " background-position: top $position;";

		$attachment = get_theme_mod( 'background_attachment', 'scroll' );
		if ( ! in_array( $attachment, array( 'fixed', 'scroll' ) ) )
			$attachment = 'scroll';
		$attachment = " background-attachment: $attachment;";

		$style .= $image . $repeat . $position . $attachment;
	}
?>
<style type="text/css" id="custom-background-css">
#bg { <?php echo trim( $style ); ?> }
</style>
<?php
}
endif;


if ( ! function_exists( 'progo_slide_layouts' ) ):
/**
 * overwrite default custom bg callback
 *
 * @since Business Pro 1.4.0
 */
function progo_slide_layouts() {	
	$layouts = array(
		//1 => 'Lots of room for Text on the Left, Form on the Right',
		2 => 'Text as Caption on Left, Form on Right',
		3 => 'Text Left, Image Right, Form below',
		4 => 'Text on the Right, Form below'
	);
	return $layouts;
}
endif;