<?php
/**
 * @package ProGo
 * @subpackage Business Pro
 * @since Business Pro 1.0
 *
 * Defines all the functions, actions, filters, widgets, etc., for ProGo Themes' Business Pro theme.
 *
 * Most Action / Filters hooks are set in the progo_setup function, below. overwriting that could cause quite a few things to go wrong.
 */

$content_width = 594;

/** Tell WordPress to run progo_setup() when the 'after_setup_theme' hook is run. */
add_action( 'after_setup_theme', 'progo_setup' );

if ( ! function_exists( 'progo_setup' ) ):
/**
 * Sets up theme defaults and registers support for various WordPress features.
 *
 * @uses register_nav_menus() to add support for navigation menus.
 * @uses add_theme_support( 'custom-background' ) to add support for a custom background.
 * @uses add_theme_support( 'post-thumbnails' ) to add support for post thumbnails.
 *
 * @since Business Pro 1.0
 */
function progo_setup() {
	// This theme styles the visual editor with editor-style.css to match the theme style
	add_editor_style( 'css/editor-style.css' );
	
	// Load up our theme options page and related code.
	require( get_template_directory() . '/inc/theme-options.php' );
	
	// This theme uses wp_nav_menu() in two locations
	$options = progo_get_theme_options();
	$menus = array(
		'mainmenu' => 'Main Menu',
		'fbarlnx' => 'Footer Bar Links',
		'ftrlnx' => 'Additional Footer Links',
		'ppcmenu' => 'Optional PPC Page Menu'
	);
	register_nav_menus( $menus );
	
	// Add support for custom backgrounds
	add_theme_support( 'custom-background', array(
		'default-image' => progo_get_default_custom_bg(),
		'wp-head-callback' => 'progo_custom_background_cb'
	) );
	
	if ( ! function_exists( 'get_custom_header' ) ) {
		// This is all for compatibility with versions of WordPress prior to 3.4.
		add_custom_background();
	}
	
	// Add support for post thumbnails
	add_theme_support( 'post-thumbnails' );
	add_image_size( 'large', 596, 397, true );
	add_image_size( 'homeslide', 960, 445, true );
	add_image_size( 'homeslide3', 480, 270, true );
	
	// Add custom actions
	add_action( 'admin_init', 'progo_admin_init' );
	add_action( 'widgets_init', 'progo_businesspro_widgets' );
	add_action( 'admin_menu', 'progo_admin_menu_cleanup', 200 );
	add_action( 'login_head', 'progo_custom_login_logo' );
	add_action( 'login_headerurl', 'progo_custom_login_url' );
	add_action( 'save_post', 'progo_save_meta' );
	add_action( 'wp_print_scripts', 'progo_add_scripts' );
	add_action( 'wp_print_styles', 'progo_add_styles' );
	add_action( 'admin_bar_menu', 'progo_admin_bar_menu', 88 );
	add_action( 'progo_poweredby', 'progo_powered_by' );
	
	// add custom filters
	add_filter( 'body_class', 'progo_bodyclasses' );
	add_filter( 'wp_nav_menu_objects', 'progo_menufilter', 10, 2 );
	add_filter( 'site_transient_update_themes', 'progo_update_check' );
	add_filter( 'admin_post_thumbnail_html', 'progo_admin_post_thumbnail_html' );
	add_filter( 'wp_mail_content_type', 'progo_mail_content_type' );
	add_filter( 'embed_oembed_html', 'progo_oembed_fix', 10, 3 );
	add_filter( 'custom_menu_order', 'progo_admin_menu_order', 99 );
	add_filter( 'menu_order', 'progo_admin_menu_order', 99 );
	// force some metaboxes turned ON
	add_filter('get_user_option_managenav-menuscolumnshidden', 'progo_metaboxhidden_defaults', 10, 3 );
	
	if ( is_admin() ) {
		add_action( 'admin_notices', 'progo_admin_notices' );
	} else {
		// brick site if theme is not activated
		if ( get_option( 'progo_businesspro_apiauth' ) != 100 ) {
			add_action( 'template_redirect', 'progo_to_twentyten' );
		}
	}
}
endif;

/********* Front-End Functions *********/

if ( ! function_exists( 'progo_sitelogo' ) ):
/**
 * prints out the HTML for the #logo area in the header of the front-end of the site
 * wrapped so child themes can overwrite if desired
 * @since Business Pro 1.0
 */
function progo_sitelogo() {
	$options = progo_get_theme_options();
	$progo_logo = $options['logo'];
	$upload_dir = wp_upload_dir();
	$dir = trailingslashit($upload_dir['baseurl']);
	$imagepath = $dir . $progo_logo;
	if($progo_logo) {
		echo '<table id="logo"><tr><td><a href="'. get_bloginfo('url') .'"><img src="'. esc_attr( $imagepath ) .'" alt="'. esc_attr( get_bloginfo( 'name' ) ) .'" /></a></td></tr></table>';
	} else {
		echo '<a href="'. get_bloginfo('url') .'" id="logo">'. esc_html( get_bloginfo( 'name' ) ) .'<span class="g"></span></a>';
	}
}
endif;
if ( ! function_exists( 'progo_nav_fallback' ) ):
/**
 * fallback callback for header nav menu
 * @since BusinessPro 1.2.1
 */
function progo_nav_fallback() {
	echo '<ul class="menu" id="nav">';
	wp_list_pages('title_li=');
	echo '</ul>';
}
endif;
if ( ! function_exists( 'progo_posted_on' ) ):
/**
 * Prints HTML with meta information for the current post—date/time and author.
 * @since ProGo Business Pro 1.0
 */
function progo_posted_on() {
	printf( __( '<span class="meta-sep">Posted by</span> %1$s <span class="%2$s">on</span> %3$s', 'progo' ),
		sprintf( '<span class="author vcard"><a class="url fn n" href="%1$s" title="%2$s">%3$s</a></span>',
			get_author_posts_url( get_the_author_meta( 'ID' ) ),
			sprintf( esc_attr__( 'View all posts by %s', 'progo' ), get_the_author() ),
			get_the_author()
		),
		'meta-prep meta-prep-author',
		sprintf( '<a href="%1$s" title="%2$s" rel="bookmark"><span class="entry-date">%3$s</span></a>',
			get_permalink(),
			esc_attr( get_the_time() ),
			get_the_date()
		)
	);
	edit_post_link( __( 'Edit', 'progo' ), '<span class="meta-sep"> : </span> <span class="edit-link">', '</span>' );
}
endif;
if ( ! function_exists( 'progo_posted_in' ) ):
/**
 * Prints HTML with meta information for the current post (category, tags and permalink).
 * @since ProGo Business Pro 1.0
 */
function progo_posted_in() {
	/* Retrieves tag list of current post, separated by commas.
	$tag_list = get_the_tag_list( '', ', ' );
	*/
	echo 'Categories : '. get_the_category_list( ', ' );
}
endif;
if ( ! function_exists( 'progo_comments' ) ):
/**
 * walker function for comment display
 * @since Business Pro 1.0
 */
function progo_comments($comment, $args, $depth) {	
	$GLOBALS['comment'] = $comment;
	extract($args, EXTR_SKIP);
	
	if ( 'div' == $args['style'] ) {
		$tag = 'div';
		$add_below = 'comment';
	} else {
		$tag = 'li';
		$add_below = 'div-comment';
	}
	?>
	<<?php echo $tag ?> <?php comment_class(empty( $args['has_children'] ) ? '' : 'parent') ?> id="comment-<?php comment_ID() ?>">
	<?php if ( 'div' != $args['style'] ) : ?>
	<div id="div-comment-<?php comment_ID() ?>" class="comment-body">
	<?php endif; ?>
	<div class="comment-meta"><div class="comment-author vcard">
	<?php echo get_comment_author_link() ?>
	</div>
	<div class="meta"><a href="<?php echo htmlspecialchars( get_comment_link( $comment->comment_ID ) ) ?>">
		<?php
			/* translators: 1: date, 2: time */
			printf( __('%1$s at %2$s'), get_comment_date(),  get_comment_time()) ?></a><?php edit_comment_link(__('(Edit)'),'&nbsp;&nbsp;','' );
		?>
	</div>
    </div>
	<?php if ($args['avatar_size'] != 0) echo get_avatar( $comment, $args['avatar_size'] ); ?>
	<?php if ($comment->comment_approved == '0') : ?>
	<em class="comment-awaiting-moderation"><?php _e('Your comment is awaiting moderation.') ?></em>
	<br />
	<?php endif; ?>
	<?php comment_text() ?>
	
	<div class="reply">
	<?php comment_reply_link(array_merge( $args, array('add_below' => $add_below, 'depth' => $depth, 'max_depth' => $args['max_depth']))) ?>
	</div>
	<?php if ( 'div' != $args['style'] ) : ?>
	</div>
	<?php endif; ?>
	<?php
}
endif;
if ( ! function_exists( 'progo_powered_by' ) ):
function progo_powered_by() {
	print 'Powered by <a href="http://www.wordpress.org" target="_blank">WordPress</a>. Designed by <a href="http://www.progo.com/" title="WordPress Themes" target="_blank"><img src="'. get_bloginfo('template_url') .'/images/logo_admin.png" alt="WordPress Themes by ProGo" /></a>';
}
endif;
if ( ! function_exists( 'progo_oembed_fix' ) ):
function progo_oembed_fix($oembvideo, $url, $attr) {
	global $post;
	if ( $post->post_type == 'progo_homeslide' ) {
		$maxwidth = 480;
		$heightat = strpos( $oembvideo , 'height="' );
		if ( $heightat !== false ) {
			$heightat += 8;
			$nextq = strpos( $oembvideo, '"', $heightat );
			$oheight = substr( $oembvideo, $heightat, $nextq - $heightat );
			
			$widthat = strpos( $oembvideo, 'width="' ) + 7;
			$nextq = strpos( $oembvideo, '"', $widthat );
			$owidth = substr( $oembvideo, $widthat, $nextq - $widthat );
			//$oembvideo = '<div>'. $owidth .' x '. $oheight .'</div>';
			 
			$newhei = round($oheight * ( $maxwidth / $owidth ));
			$playclass = 'slideplayer';
			if ( strpos( $oembvideo, 'youtu' ) !== false ) {
				$playclass .= ' youtube';
			}
			
			$oembvideo = str_replace( 'width="'. $owidth, 'id="slideplayer'. $post->ID .'" class="'. $playclass .'" width="'. $maxwidth, str_replace( 'height="'. $oheight, 'height="'. $newhei, $oembvideo ) );
		}
	}
	return $oembvideo;
}
endif;
add_filter('embed_oembed_html', 'progo_oembed_fix', 10, 3);

/********* Back-End Functions *********/
if ( ! function_exists( 'progo_admin_menu_cleanup' ) ):
/**
 * hooked to 'admin_menu' by add_action in progo_setup()
 * @since Business Pro 1.0
 */
function progo_admin_menu_cleanup() {
	global $menu, $submenu;
	
	$sub1 = array_shift($submenu['themes.php']);
	$sub1[0] = 'Change Theme';
	$submenu['tools.php'][] = $sub1;
	$sub1 = array_pop($submenu['themes.php']);
	$sub1[0] = 'Edit Theme Files';
	$submenu['tools.php'][] = $sub1;
	// add Theme Options and Homepage Slides pages under APPEARANCE
	add_theme_page( 'Theme Options', 'Theme Options', 'edit_theme_options', 'theme_options', 'progo_theme_options_render_page' );
	rsort($submenu['themes.php']);
	
	$menu[60][0] = 'ProGo Theme';
	$menu[60][4] = 'menu-top menu-icon-progo';
	
	
	$options = progo_get_theme_options();
	if ( absint($options['fbtabs']) != 1 ) {
		foreach ( $menu as $k => $m ) {
			if ( $m[2] == 'edit.php?post_type=progo_facebooktabs' ) {
				unset($menu[$k]);
			}
		}
	}
	if ( absint($options['ppcposts']) != 1 ) {
		foreach ( $menu as $k => $m ) {
			if ( $m[2] == 'edit.php?post_type=progo_ppc' ) {
				unset($menu[$k]);
			}
		}
	}
}
endif;
if ( ! function_exists( 'progo_metaboxhidden_defaults' ) ):
function progo_metaboxhidden_defaults( $result, $option, $user ) {
	$alwayson = array();
	switch ( $option ) {
		case 'managenav-menuscolumnshidden':
			$alwayson = array( 'link-target', 'css-classes' );
			break;
	}
	if ( count( $alwayson ) > 0 ) {
		if ( is_array( $result ) ) {
			if ( count( $result ) > 0 ) {
				foreach ( $result as $k => $v ) {
					if ( in_array( $v, $alwayson) ) {
						unset( $result[$k] );
					}
				}
			}
		}
	}
	return $result;
}
endif;
if ( ! function_exists( 'progo_admin_menu_order' ) ):
function progo_admin_menu_order($menu_ord) {
	if ( ! $menu_ord ) return true;
	return array(
		'index.php', // this represents the dashboard link
		'separator1',
		'themes.php', // which we changed to ProGo Theme menu area
		'edit.php?post_type=progo_facebooktabs',
		'edit.php?post_type=progo_ppc',
//		'admin.php?page=wpcf7', // failed
		// to do : GRAVITY FORMS and TESTIMONIALS
		'separator2',
		'edit.php?post_type=page', // Pages
		
		'edit.php', // Posts
		'upload.php', // Media
		'edit-comments.php', // Comments
		'link-manager.php' // Links
	);
}
endif;
if ( ! function_exists( 'progo_admin_menu_finder' ) ):
/**
 * helper function to find the $key for the menu item with given $slug
 * @since Business Pro 1.0
 */
function progo_admin_menu_finder($menu, $slug) {
	$id = 0;
	foreach ( $menu as $k => $v ) {
		if( $v[2] == $slug ) {
			$id = $k;
		}
	}
	return $id;
}
endif;
if ( ! function_exists( 'progo_custom_login_logo' ) ):
/**
 * hooked to 'login_head' by add_action in progo_setup()
 * @since Business Pro 1.0
 */
function progo_custom_login_logo() {
	if ( get_option('progo_logo') != '' ) {
		#needswork
		echo "<!-- login screen here... overwrite logo with custom logo -->\n"; 
	} else { ?>
<style type="text/css">
.login h1 a { background: url(<?php bloginfo( 'template_url' ); ?>/images/logo_progo.png) no-repeat top center; height: 80px; }
</style>
<?php }
}
endif;
if ( ! function_exists( 'progo_custom_login_url' ) ):
/**
 * hooked to 'login_headerurl' by add_action in progo_setup()
 * @uses get_option() To check if a custom logo has been uploaded to the back end
 * @return the custom URL
 * @since Business Pro 1.0
 */
function progo_custom_login_url() {
	if ( get_option( 'progo_logo' ) != '' ) {
		return get_bloginfo( 'url' );
	} // else
	return 'http://www.progo.com';
}
endif;
if ( ! function_exists( 'progo_admin_page_styles' ) ):
/**
 * hooked to 'admin_print_styles' by add_action in progo_setup()
 * adds thickbox js for WELCOME screen styling
 * @since Business Pro 1.0
 */
function progo_admin_page_styles() {
	global $pagenow;
	if ( in_array($pagenow, array( 'themes.php', 'admin.php' ) ) && isset( $_GET['page'] ) ) {
		if ( 'theme_options' == $_GET['page'] ) {
				wp_enqueue_style( 'global' );
				wp_enqueue_style( 'wp-admin' );
				wp_enqueue_style( 'thickbox' );
				wp_enqueue_style('farbtastic');
		}
	}
	wp_enqueue_style( 'progo_admin', get_bloginfo( 'template_url' ) .'/css/admin-style.css' );
}
endif;
if ( ! function_exists( 'progo_admin_page_scripts' ) ):
/**
 * hooked to 'admin_print_scripts' by add_action in progo_setup()
 * adds thickbox js for WELCOME screen Recommended Plugin info
 * @since Business Pro 1.0
 */
function progo_admin_page_scripts() {
	global $pagenow;
	if ( in_array($pagenow, array( 'themes.php', 'admin.php' ) ) && isset( $_GET['page'] ) ) {
		switch ( $_GET['page'] ) {
			case 'theme_options':
        		wp_enqueue_script( 'thickbox' );
				break;
        }
	}
}
endif;
if ( ! function_exists( 'progo_admin_init' ) ):
/**
 * hooked to 'admin_init' by add_action in progo_setup()
 * sets admin action hooks
 * registers Site Settings
 * @since Business Pro 1.0
 */
function progo_admin_init() {
	global $pagenow;
	if ( isset( $_REQUEST['progo_admin_action'] ) ) {
		$act = $_REQUEST['progo_admin_action'];
		if ( substr( $act, 0, 5 ) == 'color' ) {
			$color = substr( $act, 5 );
			$colors = progo_colorschemes();
			if ( in_array( $color, $colors ) ) {
				progo_colorscheme_switch( $color );
			}
		}
		
		switch( $act ) {
			case 'reset_logo':
				progo_reset_logo();
				break;
			case 'permalink_recommended':
				progo_permalink_check( 'recommended' );
				break;
			case 'permalink_default':
				progo_permalink_check( 'default' );
				break;
			case 'menus_set':
				progo_menus_set();
				break;
			case 'firstform':
				progo_firstform();
				break;
			case 'firstform_set':
				progo_firstform_set();
				break;
		}
	}
	
	if ( $pagenow == 'admin.php' && isset( $_GET['page'] ) ) {
		if ( $_GET['page'] == 'progo_admin' ) {
			wp_redirect( admin_url( 'themes.php?page=theme_options' ) );
		}
	}
	
	// ACTION hooks
	add_action( 'admin_print_styles', 'progo_admin_page_styles' );
	add_action( 'admin_print_scripts', 'progo_admin_page_scripts' );
	
	// since there does not seem to be an actual THEME_ACTIVATION hook, we'll fake it here
	if ( get_option( 'progo_businesspro_installed' ) != true ) {
		// also want to create a few other pages (Terms & Conditions, Privacy Policy), set up the FOOTER menu, and add these pages to it...
		
		$post_date = date( "Y-m-d H:i:s" );
		$post_date_gmt = gmdate( "Y-m-d H:i:s" );
		
		// create new menus in the Menu system
		$new_menus = array(
			'mainmenu' => '1 Main Menu',
			'fbarlnx' => '2 Footer Bar Links',
			'ftrlnx' => '3 Additional Footer Links',
			'ppcmenu' => 'Optional PPC Primary Menu',
		);
		$aok = 1;
		foreach ( $new_menus as $k => $m ) {
			$new_menus[$k] = wp_create_nav_menu($m);
			if ( is_numeric( $new_menus[$k] ) == false ) {
				$aok--;
			}
		}
		//set_theme_mod
		if ( $aok == 1 ) {
			// register the new menus as THE menus in theme's menu areas
			set_theme_mod( 'nav_menu_locations' , $new_menus );
		}
		
		// create a few new pages, and populate some menus
		$lipsum = "Lorem ipsum dolor sit amet, consectetuer adipiscing elit, sed diam nonummy nibh euismod tincidunt ut laoreet dolore magna aliquam Lorem ipsum dolor sit amet, consectetuer adipiscing elit, sed diam nonummy nibh euismod tincidunt ut laoreet dolore magna aliquam...Lorem ipsum dolor sit amet, consectetuer adipiscing elit, sed diam nonummy nibh euismod tincidunt ut laoreet dolore magna\n\nLorem ipsum dolor sit amet, consectetuer adipiscing elit, sed diam nonummy nibh euismod tincidunt ut laoreet dolore magna aliquam Lorem ipsum dolor sit amet, consectetuer adipiscing elit, sed diam nonummy nibh euismod tincidunt ut laoreet dolore magna aliquam...Lorem ipsum dolor sit amet, consectetuer adipiscing elit, sed diam nonummy nibh euismod tincidunt ut laoreet dolore magna aliquam";
		
		$new_pages = array(
			'home' => array(
				'title' => __( 'Home', 'progo' ),
				'content' => "<h2>This is your Homepage</h2>\n$lipsum\n\n[smbcallout headline=\"Don't Wait - Request an Appointment|Today With a Profesional\" lnk=\"#top\" call=\"GET STARTED\"]",
				'id' => '',
				'menus' => array( 'mainmenu', 'ppcmenu' )
			),
			'about' => array(
				'title' => __( 'About Us', 'progo' ),
				'content' => "<h2>This Page could have info about your site</h2>\n$lipsum",
				'id' => '',
				'menus' => array( 'mainmenu', 'fbarlnx', 'ppcmenu' )
			),
			'blog' => array(
				'title' => __( 'Blog', 'progo' ),
				'content' => "This Page pulls in your Blog posts",
				'id' => '',
				'menus' => array( 'mainmenu', 'fbarlnx' )
			),
			'terms' => array(
				'title' => __( 'Terms & Conditions', 'progo' ),
				'content' => "<h2>List your Terms and Conditions here</h2>\n$lipsum",
				'id' => '',
				'menus' => array( 'ftrlnx' )
			),
			'privacy' => array(
				'title' => __( 'Privacy Policy', 'progo' ),
				'content' => "<h2>Put your Privacy Policy here</h2>\n$lipsum",
				'id' => '',
				'menus' => array( 'ftrlnx' )
			),
			'customer-service' => array(
				'title' => __( 'Customer Service', 'progo' ),
				'content' => "<h2>This Page could have Customer Service info on it</h2>\n$lipsum",
				'id' => '',
				'menus' => array( 'ftrlnx' )
			)
		);
		foreach ( $new_pages as $slug => $page ) {
			$new_pages[$slug]['id'] = wp_insert_post( array(
				'post_title' 	=>	$page['title'],
				'post_type' 	=>	'page',
				'post_name'		=>	$slug,
				'comment_status'=>	'closed',
				'ping_status' 	=>	'closed',
				'post_content' 	=>	$page['content'],
				'post_status' 	=>	'publish',
				'post_author' 	=>	1,
				'menu_order'	=>	1
			));
			
			if ( $new_pages[$slug]['id'] != false ) {
				// set "Home" & "Blog" page IDs
				switch ( $slug ) {
					case 'home':
						update_option( 'page_on_front', $new_pages[$slug]['id'] );
						update_option( 'progo_homepage_id', $new_pages[$slug]['id'] );
						break;
					case 'blog':
						update_option( 'page_for_posts', $new_pages[$slug]['id'] );
						update_option( 'progo_blog_id', $new_pages[$slug]['id'] );
						
						// also add SAMPLE PAGE (pageid=2) to menus, before BLOG
						$menu_args = array(
							'menu-item-object-id' => 2,
							'menu-item-object' => 'page',
							'menu-item-parent-id' => 0,
							'menu-item-type' => 'post_type',
							'menu-item-title' => 'Sample Page',
							'menu-item-status' => 'publish',
						);
						$samplemenus = array( 'mainmenu', 'ppcmenu' );
						foreach ( $samplemenus as $sm ) {
							$menu_id = $new_menus[$sm];
							if ( is_numeric( $menu_id ) ) {
								wp_update_nav_menu_item( $menu_id , 0, $menu_args );
							}
						}
						break;
				}
				
				$menu_args = array(
					'menu-item-object-id' => $new_pages[$slug]['id'],
					'menu-item-object' => 'page',
					'menu-item-parent-id' => 0,
					'menu-item-type' => 'post_type',
					'menu-item-title' => $page['title'],
					'menu-item-status' => 'publish',
				);
				foreach ( $new_pages[$slug]['menus'] as $menu_key ) {
					$menu_id = $new_menus[$menu_key];
					if ( is_numeric( $menu_id ) ) {
						wp_update_nav_menu_item( $menu_id , 0, $menu_args );
					}
				}
			}
		}
		// and lets also add our first HOMEPAGE SLIDE ?
		$slide1 = wp_insert_post( array(
			'post_title' 	=>	'Get Your Customers What They Need Most!',
			'post_type' 	=>	'progo_homeslide',
			'post_name'		=>	'slide1',
			'comment_status'=>	'closed',
			'ping_status' 	=>	'closed',
			'post_content' 	=>	"Lorem ipsum dolor sit amet, consectetuer adipiscing elit, sed diam nonummy nibh euismod tincidunt ut laoreet dolore magna aliquam.  Lorem ips\n\n<a href=\"". trailingslashit(get_bloginfo('url')) ."about/\" class=\"button\">CALL TO ACTION</a>",
			'post_status' 	=>	'publish',
			'post_author' 	=>	1,
			'menu_order'	=>	1
		));
		
		// set our default SITE options
		progo_options_defaults();
		
		// and send to WELCOME page
		wp_redirect( get_option( 'siteurl' ) . '/wp-admin/themes.php?page=theme_options' );
	}
}
endif;
if ( ! function_exists( 'progo_admin_head_fix' ) ):
function progo_admin_head_fix(){
global $post;

echo' <style type="text/css">#postdiv.postarea, #postdivrich.postarea { margin:0; } #post-status-info { line-height:1.4em; font-size:13px; } #custom_editor .inside { margin:2px 6px 6px 6px; } #ed_toolbar { display:none; } #postdiv #ed_toolbar, #postdivrich #ed_toolbar { display:block; }
</style>';

	if ( get_post_type($post) == 'progo_facebooktabs') {
?>
<script type='text/javascript' src='https://ajax.googleapis.com/ajax/libs/jquery/1.7.2/jquery.min.js'></script>
	
	<script type='text/javascript'>//<![CDATA[ 
jQuery(document).ready(function() {
	show_hide_meta_box();
    jQuery("input[name$='FB_Tab_meta_template']").click(function() { show_hide_meta_box();	});	
});
function show_hide_meta_box() {
	var test;
	 jQuery("input[name$='FB_Tab_meta_template']").each(function(){
		if($(this).is(':checked'))
			test = $(this).val();
	 });
	 test == 2 ? jQuery('#fbIImage').show() : jQuery('#fbIImage').hide();
	test == 1? jQuery('#custom_menu_meta_box ').show() : jQuery('#custom_menu_meta_box ').hide();
	if(test == 3 || test == 6){
		jQuery('#custom_sub_head_meta_box').show();
		jQuery('#custom_pre_head_meta_box').hide();
	}else{
		jQuery('#custom_sub_head_meta_box').hide();
		jQuery('#custom_pre_head_meta_box').show();
	}	
	if(test == 4 || test == 5 || test == 6) jQuery('#fbVideo').show(); else jQuery('#fbVideo').hide(); 
}
//]]>  

</script>

<?php
	}
	
	if ( get_post_type($post) == 'progo_ppc') {
?>
<script type='text/javascript' src='https://ajax.googleapis.com/ajax/libs/jquery/1.7.2/jquery.min.js'></script>
	
	<script type='text/javascript'>//<![CDATA[ 
jQuery(document).ready(function() {
	show_hide_meta_box();
    jQuery("input[name$='PPC_meta_template']").click(function() { show_hide_meta_box();	});	
});
function show_hide_meta_box() {
	var test;
	 jQuery("input[name$='PPC_meta_template']").each(function(){
		if($(this).is(':checked'))
			test = $(this).val();
	 });
	 	if(test == 1){
		jQuery('#progo_ppc_form_content').show();
		
	}else{
		jQuery('#progo_ppc_form_content').hide();
		
	}
}
//]]>  

</script>


<?php
	}
}
endif;
add_action('admin_head', 'progo_admin_head_fix');

if ( ! function_exists( 'progo_admin_footer_fix' ) ):
function progo_admin_footer_fix(){
global $post;
	if ( get_post_type($post) == 'progo_facebooktabs') {
?>

	<script type="text/javascript">
		jQuery('#postdiv, #postdivrich').prependTo('#custom_editor .inside');
	</script>
	

<?php
	}
	
	if ( get_post_type($post) == 'progo_ppc') {
?>

	<script type="text/javascript">
		jQuery('#postdiv, #postdivrich').prependTo('#custom_editor .inside');
	</script>
	

<?php
	}
}
endif;
add_action('admin_footer','progo_admin_footer_fix');

if ( ! function_exists( 'FB_Tab_meta_headline' ) ):
	function FB_Tab_meta_headline($post){    
    $FB_Tab_meta_headline = get_post_meta($post->ID, '_FB_Tab_meta_headline', true);
   ?>
   <input id="FB_Tab_meta_headline" type="text" size="75" name="FB_Tab_meta_headline" value="<?php echo esc_attr($FB_Tab_meta_headline); ?>" />
		
    <?php		
    }
    endif;
    
	
	function FB_Tab_iframe_code($post){
		$postID = $post->ID;
	?> 
	<strong>1)</strong> <span class="description">Setup Facebook Fan Page:<br />
	<div style="background-color:#666; color:#fff;">Install App on Fan Page: <a href="https://apps.facebook.com/static_html_plus/?fb_page_id=248250431889836" target="_blank" style="background-color:#fff;">Install Static HTML Application</a>
	<br />
	<br />Create Facebook Fan Page Tutorial: <a href="http://www.opace.co.uk/blog/how-to-install-a-static-html-iframe-tab" target="_blank" style="background-color:#fff;">View Tutorial</a>
	</span></div>
  <br />
  <strong>2)</strong> <span class="description">Copy and Paste this Iframe code into your Facebook Fan Page Static HTML content area:</span> <div style="background-color:#666; color:#fff;"><xmp><iframe width="851px" height="100%" scrolling="no" src="<?php echo get_permalink( $postID ); ?>">
</iframe></xmp></div>

<br />
<strong>3)</strong> <span class="description">Copy and Paste this code into your Facebook Tab content window above to add a second LIKE ME button at the bottom of your offer:</span><div style="background-color:#666; color:#fff;"><xmp><div class="fb-like" data-href="ADD YOUR FACEBOOK FAN PAGE URL HERE" data-send="true" 
data-width="450" data-show-faces="true"></div></xmp></div>
		
    <?php
	
	}
	
		
if ( ! function_exists( 'FB_Tab_meta_form' ) ):
	function FB_Tab_meta_form($post){    
    $FB_Tab_meta_form = get_post_meta($post->ID, '_FB_Tab_meta_form', true);
   ?> 
    Form Shortcode: <input id="FB_Tab_meta_form" type="text" size="75" name="FB_Tab_meta_form" value="<?php echo esc_attr($FB_Tab_meta_form); ?>" />
		
    <?php		
    }
    endif;
    
    
    if ( ! function_exists( 'FB_Tab_meta_video' ) ):
	function FB_Tab_meta_video($post){
    $FB_Tab_meta_video = get_post_meta($post->ID, '_FB_Tab_meta_video', true);
    $FB_Tab_meta_thumb = get_post_meta($post->ID, '_FB_Tab_meta_thumb', true);
    $FB_Tab_meta_template = get_post_meta($post->ID, '_FB_Tab_meta_template', true);
   
   ?>
  	<div id="myTemplateGroup">
    <div id="FB_Tab_meta_template" class="desc" >
      Video URL: <input id="FB_Tab_meta_video" type="text" size="75" name="FB_Tab_meta_video" value="<?php echo esc_attr($FB_Tab_meta_video); ?>" />
	<br />
	<hr />
	<br />
	Thumbnail URL: <input id="FB_Tab_meta_thumb" type="text" size="75" name="FB_Tab_meta_thumb" value="<?php echo esc_attr($FB_Tab_meta_thumb); ?>" /> 	
    </div>
	</div>
	<?php 
    }
    endif;
    
     if ( ! function_exists( 'FB_Tab_meta_template' ) ):
/**
 * outputs HTML for "Facebook Tab Cust Post types" option on FB Tab Settings post
 * @since Business Pro 1.0
 */
    function FB_Tab_meta_template($post){    
    $FB_Tab_meta_template = get_post_meta($post->ID, '_FB_Tab_meta_template', true);
  

	$layouton = $FB_Tab_meta_template;
	if ( $layouton < 1 || $layouton > 6 ) {
		$layouton = 1;
	}
	$descriptions = array(
		1 => 'Non Fan Public Content (Template 1)',
		2 => 'Video, Contact Form and Content (Template 2)',
		3 => 'Non Fan Public Content (Template 3)',
		4 => 'Fans Only Content (Template 4)',
		5 => 'Custom first Content (Template 5)',
		6 => 'Custom second Content (Template 6)'
	);
//	echo '<pre style="display:none">'. print_r($options,true) .'</pre>';
	?>
    <table>
    <tr valign="top">
<?php
	for ( $i=1; $i<7; $i++ ) {
		$chk = $layouton==$i ? ' checked="checked"' : '';
		$last = "";
		if($i == 3 || $i == 6) $last = "last_temp_img";
		echo '<td class="template_radio_but"><input type="radio" name="FB_Tab_meta_template" id="fblayout'. $i .'" value="'. $i .'"'. $chk .' /></td><td class="template_img '.$last.'"><label for="fblayout'. $i .'"><img src="'. get_bloginfo('template_url') .'/images/FBTabTemplateImages/'. $i .'.jpg" /><br /><strong>Layout '. $i .'</strong></label><br /><span class="description">'. $descriptions[$i] .'</span></td>';
		if($i == 3) echo "</tr><tr>";
		//<option value="'. $color .'"'. (($options['colorscheme']==$color) ? ' selected="selected"' : '') .'>'.esc_html($color).'</option>';
	}
	?></tr></table>
	<?php
	}
endif;
if ( ! function_exists( 'progo_businesspro_init' ) ):
/**
 * registers our "Homepage Slides" Custom Post Type
 * @since Business Pro 1.0
 */
function progo_businesspro_init() {
	// HOMESLIDER SLIDES
	register_post_type( 'progo_homeslide',
		array(
			'labels' => array(
				'name' => _x('Homepage Slides', 'post type general name'),
				'singular_name' => _x('Slide', 'post type singular name'),
				'add_new_item' => _x('Add New Slide', 'Homepage Slides'),
				'edit_item' => __('Edit Slide'),
				'new_item' => __('New Slide'),
				'view_item' => __('View Slide'),
				'search_items' => __('Search Slides'),
				'not_found' =>  __('No slides found'),
				'not_found_in_trash' => __('No slides found in Trash'), 
				'parent_item_colon' => '',
				'menu_name' => __('Homepage Slides')
			),
			'public' => true,
			'public_queryable' => true,
			'exclude_from_search' => true,
			'show_in_menu' => 'themes.php',
			'hierarchical' => true,
			'supports' => array( 'title', 'editor', 'thumbnail', 'revisions', 'page-attributes' ),
		)
	);

	// Facebook Tabs
	register_post_type( 'progo_facebooktabs', array(
		'labels' => array(
			'name' => _x('Facebook Tabs', 'post type general name'),
			'singular_name' => _x('Facebook Tabs', 'post type singular name'),
			'add_new' => _x('Add New', 'Facebook Tabs'),
			'add_new_item' => __('Add New Tab'),
			'edit_item' => __('Edit Tab'),
			'new_item' => __('New Tab'),
			'view_item' => __('View Tab'),
			'search_items' => __('Facebook'),
			'not_found' =>  __('No Tab found'),
			'not_found_in_trash' => __('No Tab found in Trash'), 
			'parent_item_colon' => ''
		),
		'public' => true,
		'publicly_queryable' => true,
		'show_ui' => true, 
		'query_var' => true,
		'rewrite' => array(
			'slug' => 'fb',
		),
		'capability_type' => 'post',
		'hierarchical' => false,
		'supports' => array( 'title', 'editor', 'custom-fields' ),
	));
	
	// PPC POSTS
	register_post_type( 'progo_ppc', array(
		'labels' =>  array(
			'name' => _x('PPC Posts', 'post type general name'),
			'singular_name' => _x('PPC Posts', 'post type singular name'),
			'add_new' => _x('Add New', 'PPC'),
			'add_new_item' => __('Add New PPC'),
			'edit_item' => __('Edit PPC'),
			'new_item' => __('New PPC'),
			'view_item' => __('View PPC'),
			'search_items' => __('PPC'),
			'not_found' =>  __('No PPC Posts found'),
			'not_found_in_trash' => __('No PPC Posts found in Trash'), 
			'parent_item_colon' => '',
			'menu_name' => 'PPC Posts',
			'description' => "Shortcodes: [keyword loc=1], [keyword loc=2], the 1st and 2nd words after ppc/."
		),
		'public' => true,
		'publicly_queryable' => true,
		'show_ui' => true, 
		'query_var' => true,
		'rewrite' => array(
			'slug' => 'ppc',
		),
		'capability_type' => 'post',
		'hierarchical' => false,
		'supports' => array( 'title', 'editor', 'custom-fields', 'comments' ),
	));
}
add_action( 'init', 'progo_businesspro_init' );
endif;
if ( ! function_exists( 'progo_custom_updated_messages' ) ):
function progo_custom_updated_messages( $messages ) {
  global $post, $post_ID;

 $messages['progo_homeslide'] = array(
    0 => '', // Unused. Messages start at index 1.
    1 => sprintf( __('Slide updated. <a href="%s">View Home Page</a>'), get_bloginfo( 'url' ) ),
    2 => __('Custom field updated.'),
    3 => __('Custom field deleted.'),
    4 => __('Slide updated.'),
    /* translators: %s: date and time of the revision */
    5 => isset($_GET['revision']) ? sprintf( __('Slide restored to revision from %s'), wp_post_revision_title( (int) $_GET['revision'], false ) ) : false,
    6 => sprintf( __('Slide published. <a href="%s">View Home Page</a>'), get_bloginfo( 'url' ) ),
    7 => __('Hot Tub saved.'),
    8 => sprintf( __('Slide submitted.') ),
    9 => sprintf( __('Slide scheduled for: <strong>%1$s</strong>'),
      // translators: Publish box date format, see http://php.net/date
      date_i18n( __( 'M j, Y @ G:i' ), strtotime( $post->post_date ) ) ),
    10 => sprintf( __('Slide draft updated.') ),
  );
  return $messages;
}
endif;
add_filter( 'post_updated_messages', 'progo_custom_updated_messages' );

if ( ! function_exists( 'FB_Template' ) ):

function FB_Template($single_template) {
	global $post;
	
	if ($post->post_type == 'progo_facebooktabs') {
	
		$fbt = absint(get_post_meta($post->ID, '_FB_Tab_meta_template', true));
		if ( $fbt < 2 || $fbt > 6 ) {
			$fbt = 1;
		}
		$single_template = dirname( __FILE__ ) . '/facebook-tab-page'. $fbt .'.php';
	}
	return $single_template;
}

endif;
add_filter( "single_template", "FB_Template" ) ;
if ( ! function_exists( 'progo_businesspro_widgets' ) ):
/**
 * registers a sidebar area for the WIDGETS page
 * and registers various Widgets
 * @since Business Pro 1.0
 */
function progo_businesspro_widgets() {
	register_sidebar(array(
		'name' => 'Blog',
		'id' => 'blog',
		'description' => 'Sidebar for the Blog area',
		'before_widget' => '<div class="block %1$s %2$s">',
		'after_widget' => '</div>',
		'before_title' => '<h3 class="title"><span class="spacer">',
		'after_title' => '</span></h3>'
	));
	register_sidebar(array(
		'name' => 'Homepage Sidebar',
		'id' => 'home',
		'description' => 'Right column sidebar area on Homepage',
		'before_widget' => '<div class="block %1$s %2$s">',
		'after_widget' => '</div>',
		'before_title' => '<h3 class="title"><span class="spacer">',
		'after_title' => '</span></h3>'
	));
	register_sidebar(array(
		'name' => 'Standard Pages',
		'id' => 'main',
		'description' => 'Standard right column sidebar area',
		'before_widget' => '<div class="block %1$s %2$s">',
		'after_widget' => '</div>',
		'before_title' => '<h3 class="title"><span class="spacer">',
		'after_title' => '</span></h3>'
	));
	register_sidebar(array(
		'name' => 'Contact',
		'id' => 'contact',
		'description' => 'Optional Contact/About page sidebar',
		'before_widget' => '<div class="block %1$s %2$s">',
		'after_widget' => '</div>',
		'before_title' => '<h3 class="title"><span class="spacer">',
		'after_title' => '</span></h3>'
	));
	register_sidebar(array(
		'name' => 'Header',
		'id' => 'header',
		'description' => 'We can put a widget or two in the top right of the header',
		'before_widget' => '<div class="hblock %1$s %2$s">',
		'after_widget' => '</div>',
		'before_title' => '<h3 class="title"><span class="spacer">',
		'after_title' => '</span></h3>'
	));
	register_sidebar(array(
		'name' => 'Bottom Bar',
		'id' => 'fbar',
		'description' => 'Bar along the bottom of each page. If left blank, will display the Bottom Bar menu.',
		'before_widget' => '<div class="fblock %1$s %2$s">',
		'after_widget' => '</div>',
		'before_title' => '<div class="title">',
		'after_title' => '</div>'
	));
	register_sidebar(array(
		'name' => 'PPC Template',
		'id' => 'ppc-template',
		'description' => 'PPC Post Widgets Go Here',
		'before_widget' => '<div class="block %1$s %2$s">',
		'after_widget' => '</div>',
		'before_title' => '<h3 class="title"><span class="spacer">',
		'after_title' => '</span></h3>'
	));
	
	$progo_widgets = array( 'FBLikeBox', 'Tweets', 'Share', 'Social', 'Support', 'PBPForm', 'OfficeInfo' );
	foreach ( $progo_widgets as $w ) {
		require_once( 'widgets/widget-'. strtolower($w) .'.php' );
		register_widget( 'ProGo_Widget_'. $w );
	}
}
endif;

	
function blank(){}
if ( ! function_exists( 'progo_metabox_cleanup' ) ):
/**
 * fires after wpsc_meta_boxes hook, so we can overwrite a lil bit
 * @since Business Pro 1.0
 */
function progo_metabox_cleanup() {
	global $wp_meta_boxes;
	global $post_type;
	global $post;
	

	foreach (array('page','post','progo_facebooktabs','progo_ppc') as $type)
	{
		add_meta_box('custom_editor', 'Content', 'blank', $type, 'normal', 'high');
	}
	
	switch($post_type) {
		case 'page':
			add_meta_box( 'progo_sidebar_box', 'Sidebar', 'progo_sidebar_box', 'page', 'side', 'low' );
			break;
		case 'progo_homeslide':
			$wp_meta_boxes['progo_homeslide']['side']['low']['postimagediv']['title'] = 'Slide Background Image';
			
			// no need for SEO metaboxes on Homeslides
			if(isset($wp_meta_boxes['progo_homeslide']['normal']['high']['wpseo_meta'])) unset($wp_meta_boxes['progo_homeslide']['normal']['high']['wpseo_meta']);
			break;
		case 'progo_testimonials':
			if(isset($wp_meta_boxes['progo_testimonials']['normal']['high']['wpseo_meta'])) unset($wp_meta_boxes['progo_testimonials']['normal']['high']['wpseo_meta']);
			break;
		case 'progo_ppc':
			add_meta_box ('progo_ppc_templates', 'Select A Template', 'progo_ppc_templates', 'progo_ppc', 'normal', 'high' );
			add_meta_box ('progo_ppc_form_title', 'Form Title', 'progo_ppc_form_title', 'progo_ppc', 'normal', 'high' );
			add_meta_box ('progo_ppc_form_shortcode', 'Form Shortcode', 'progo_ppc_form_shortcode', 'progo_ppc', 'normal', 'high' );
			add_meta_box ('progo_ppc_form_content', 'Below Form Content', 'progo_ppc_form_content', 'progo_ppc', 'normal', 'high' );
			add_meta_box ('progo_ppc_shortcodes', 'PPC Overview', 'progo_ppc_list_shortcodes', 'progo_ppc', 'normal', 'high' );
			break;
		case 'progo_facebooktabs':
			$temp = get_post_meta($post->ID, '_FB_Tab_meta_template', true);
			add_meta_box ('fbTemplate', 'Select a Template', 'FB_Tab_meta_template', 'progo_facebooktabs', 'normal', 'high' );
			add_meta_box('custom_pre_head_meta_box', 'Preheadline', 'add_fb_pre_headline', 'progo_facebooktabs', 'normal', 'high');
			add_meta_box('custom_sub_head_meta_box', 'Subheadline', 'add_fb_sub_headline', 'progo_facebooktabs', 'normal', 'high');
			add_meta_box ('fbIImage', 'Featured Image', 'FB_featured_image', 'progo_facebooktabs', 'normal', 'high' );
			add_meta_box ('fbHeadline', 'Headline', 'FB_Tab_meta_headline', 'progo_facebooktabs', 'normal', 'high' );
			add_meta_box ('fbForm', 'Form Shortcode', 'FB_Tab_meta_form', 'progo_facebooktabs', 'normal', 'high' );
			add_meta_box ('fbVideo', 'Video Settings', 'FB_Tab_meta_video', 'progo_facebooktabs', 'normal', 'high' );
			add_meta_box ('fbIFrame', 'Facebook Fan Page Resources', 'FB_Tab_iframe_code', 'progo_facebooktabs', 'normal', 'high' );
			break;
	}
}
endif;
add_action( 'do_meta_boxes', 'progo_metabox_cleanup' );
if ( ! function_exists( 'progo_sidebar_box' ) ):
/**
 * outputs html for "Sidebar" meta box on EDIT PAGE
 * lets Admins choose which Sidebar area is displayed on each Page
 * called by add_meta_box( "progo_direct_box", "Direct Response", "progo_direct_box"...
 * in progo_admin_init()
 * @uses progo_direct_meta_defaults()
 * @since Business Pro 1.0
 */
function progo_sidebar_box() {
	global $post;
	$sidebar = get_post_meta($post->ID,'_progo_sidebar', true);
	if ( $sidebar == '' ) $sidebar = 'main';
	
	$ids = array('main', 'home', 'blog', 'contact');
	$titles = array('Standard sidebar', 'Homepage', 'Blog', 'Contact');
	
	if( ! in_array( $sidebar, $ids ) ) {
		$sidebar = 'main';
	}
	?>
	<p>Choose a Sidebar to display on this Page</p>
	<select name="_progo_sidebar"><?php
for ( $i = 0; $i < count($ids); $i++) {
		echo '<option value="'. $ids[$i] .'"'. ( $ids[$i] == $sidebar ? ' selected="selected"' : '' ) .'>'. esc_attr( $titles[$i] ) .'</option>';
	} ?></select>
    <p><a href="<?php echo admin_url('widgets.php'); ?>" target="_blank">Configure Widgets Here &raquo;</a></p>
	<?php
}
endif;
if ( ! function_exists( 'progo_slidecontent_box' ) ):
/**
 * custom metabox for Homepage Slides content area
 * @since Business Pro 1.0
 */
function progo_slidecontent_box() {
	global $post;
	$custom = get_post_meta($post->ID,'_progo_slidecontent');
	$content = (array) $custom[0];
	if ( ! isset( $content['text'] ) ) {
		$slidetext = "Lorem ipsum dolor sit amet, consectetuer adipiscing elit, sed diam nonummy nibh euismod tincidunt ut laoreet dolore magna aliquam.  Lorem ipsum dolor sit amet, consectetuer adipiscing elit, sed diam nonummy nibh euismod tincidunt.\n<a href=\"". trailingslashit(get_bloginfo('url')) ."/about/\"><strong>View Details</strong></a>";
	} else {
		$slidetext = $content['text'];
	}
	if ( ! isset( $content['textcolor'] ) ) {
		$content['textcolor'] = 'Light';
	}
	if ( ! isset( $content['showtitle'] ) ) {
		$content['showtitle'] = 'Show';
	}
	?>
    <div class="slidecontent" id="slidetypeTextContent">
    	<p><em>Title (above) will be used as the main text Headline for this Slide</em></p>
        <p><strong>Additional Copy (optional)</strong><br />
        <textarea name="progo_slidecontent[text]" rows="6" style="width: 100%"><?php echo esc_attr($slidetext); ?></textarea><br /><em>Line Breaks in the text above will be converted to "&lt;br /&gt;" on display</em></p>
    </div>
    <table id="slideTextColor"><tr><th scope="row" width="141" align="left">Slide Text Color :</th><?php
	
	$opts = array( 'Light', 'Dark');
	foreach ( $opts as $c ) {
		?><td width="82"><label for="slideTextColor<?php esc_attr_e( $c ); ?>"><input type="radio" name="progo_slidecontent[textcolor]" id="slideTextColor<?php esc_attr_e( $c ); ?>" value="<?php esc_attr_e( $c ); ?>" <?php checked($content['textcolor'], $c) ?> /> <?php esc_attr_e( $c ); ?></label></td><?php
	} ?></tr></table>
    <table id="showhideTitle"><tr><th scope="row" width="141" align="left">Show/Hide Slide Title :</th><?php
	
	$opts = array( 'Show', 'Hide');
	foreach ( $opts as $c ) {
		?><td width="82"><label for="showhideTitle<?php esc_attr_e( $c ); ?>"><input type="radio" name="progo_slidecontent[showtitle]" id="showhideTitle<?php esc_attr_e( $c ); ?>" value="<?php esc_attr_e( $c ); ?>" <?php checked($content['showtitle'], $c) ?> /> <?php esc_attr_e( $c ); ?></label></td><?php
	} ?></tr></table>
    <script type="text/javascript">
/* <![CDATA[ */
jQuery(function() {
	jQuery('#parent_id').hide().prevAll().hide();
	jQuery('#edit-slug-box').hide();
});
/* ]]> */
	</script>
    <?php
}
endif;
if ( ! function_exists( 'progo_ppc_list_shortcodes' ) ):
/**
 * custom metabox for PPC Posts, explaining Shortcodes
 * @since Business Pro 1.2.6
 */
function progo_ppc_list_shortcodes() {
	?>
	<p>
    <strong>Instructions / Overview</strong><br />
    <a href="<?php echo get_stylesheet_directory_uri(); ?>/instructions/PPC-Overview.pdf" target="_blank">PPC Overview</a></p>
    <p>
    <strong>Shortcodes</strong><br />
    [keyword loc=1] = http://www.site.com/ppc/KEYWORD/ -- (default)<br />
    [keyword loc=2] = http://www.site.com/ppc/word/KEYWORD/</p>
    <?php
}
endif;

if ( ! function_exists( 'progo_ppc_templates' ) ):
/**
 * custom metabox for PPC Posts, allowing the selection of a template to use.
 * @since Business Pro 1.2.6
 */
function progo_ppc_templates($post) {
	  $PPC_meta_template = get_post_meta($post->ID, '_PPC_meta_template', true);
  

	$layouton = $PPC_meta_template;
	if ( $layouton < 1 || $layouton > 4 ) {
		$layouton = 2;
	}
	$descriptions = array(
		1 => 'Large text area on top of page,<br /> Contact form in middle of page, Second text area under form',
		2 => 'Lots of room for Text on the Left, Form on the Right',
		3 => 'Lots of room for Text, Featured Image on the Right, Form below',
	);
//	echo '<pre style="display:none">'. print_r($options,true) .'</pre>';
	?>
    <table>
    <tr valign="top">
<?php
	for ( $i=1; $i<3; $i++ ) {
		$chk = $layouton==$i ? ' checked="checked"' : '';
		echo '<td><input type="radio" name="PPC_meta_template" id="PPClayout'. $i .'" value="'. $i .'"'. $chk .' /></td><td><label for="PPClayout'. $i .'"><img src="'. get_bloginfo('template_url') .'/images/PPCTemplateImages/'. $i .'.jpg" /><br /><strong>Layout '. $i .'</strong></label><br /><span class="description">'. $descriptions[$i] .'</span></td>';
		//<option value="'. $color .'"'. (($options['colorscheme']==$color) ? ' selected="selected"' : '') .'>'.esc_html($color).'</option>';
	}
	?></tr></table>
	<?php
	} 
	
endif;

if ( ! function_exists( 'progo_ppc_form_title' ) ):
/**
 * custom metabox for PPC Posts, Form Title
 * @since Business Pro 1.2.6
 */
function progo_ppc_form_title($post) {
	 $ppc_form_title = get_post_meta($post->ID, '_ppc_form_title', true);
   ?> 
    Form Title: <input id="ppc_form_title" type="text" size="75" name="ppc_form_title" value="<?php echo esc_attr($ppc_form_title); ?>" />
		
    <?php		
}
endif; 

if ( ! function_exists( 'progo_ppc_form_shortcode' ) ):
/**
 * custom metabox for PPC Posts, Form Shortcode
 * @since Business Pro 1.2.6
 */
function progo_ppc_form_shortcode($post) {
	 $ppc_form_shortcode = get_post_meta($post->ID, '_ppc_form_shortcode', true);
   ?> 
    Form Shortcode: <input id="ppc_form_shortcode" type="text" size="75" name="ppc_form_shortcode" value="<?php echo esc_attr($ppc_form_shortcode); ?>" />
		
    <?php	
}
endif;


if ( ! function_exists( 'progo_ppc_form_content' ) ):
/**
 * custom metabox for PPC Posts, Below Form Content
 * @since Business Pro 1.2.6
 */
function progo_ppc_form_content($post) {

	$settings =   array(
    'wpautop' => true, // use wpautop?
    'media_buttons' => true, // show insert/upload button(s)
    'textarea_name' => 'ppcformcontent', // set the textarea name to something different, square brackets [] can be used here
    'textarea_rows' => get_option('default_post_edit_rows', 10), // rows="..."
    'tabindex' => '',
    'editor_css' => '', // intended for extra styles for both visual and HTML editors buttons, needs to include the <style> tags, can use "scoped".
    'editor_class' => '', // add extra class(es) to the editor textarea
    'teeny' => false, // output the minimal editor config used in Press This
    'dfw' => false, // replace the default fullscreen with DFW (supported on the front-end in WordPress 3.4)
    'tinymce' => true, // load TinyMCE, can be used to pass settings directly to TinyMCE using an array()
    'quicktags' => true // load Quicktags, can be used to pass settings directly to Quicktags using an array()
	);
	
	$ppc_form_content =  wpautop(get_post_meta($post->ID, '_ppcformcontent', true));
	wp_editor( $ppc_form_content, 'ppcformcontent', $settings);
   ?> 
 
		    <?php	
}
endif;




/********* core ProGo Themes' Business Pro functions *********/

if ( ! function_exists( 'progo_add_scripts' ) ):
/**
 * hooked to 'wp_print_scripts' by add_action in progo_setup()
 * adds front-end js
 * @since Business Pro 1.0
 */
function progo_add_scripts() {
	if ( ! is_admin() ) {
		wp_enqueue_script( 'progo', get_bloginfo('template_url') .'/js/progo-frontend.js', array('jquery'), '1.0' );
		do_action('progo_frontend_scripts');
		
		if ( current_user_can('edit_theme_options') ) {
			$options = progo_get_theme_options();
			if ( (int) $options['showtips'] == 1 ) {
				wp_enqueue_script( 'progo-tooltips', get_bloginfo('template_url') .'/js/progo-tooltips.js', array('jquery'), '1.0', true );
				echo '<script type="text/javascript">var progo_adminurl = "'. admin_url('') .'";</script>';
			}
		}
	} else {
		if ( isset( $_GET['page'] ) ) {
			if ( $_GET['page'] == 'progo_admin' ) {
				wp_enqueue_script('custom-background');
			}
		}
	}
}
endif;

if ( ! function_exists( 'progo_add_styles' ) ):
/**
 * hooked to 'wp_print_styles' by add_action in progo_setup()
 * checks for Color Scheme setting and adds appropriate front-end stylesheet
 * @since Business Pro 1.0
 */
function progo_add_styles() {
	if ( ! is_admin() ) {
		$options = progo_get_theme_options();
		$color = $options['colorscheme'];
		$avail = progo_colorschemes();
		if ( in_array( $color, $avail ) ) {
			$scheme = 'progo-colorscheme';
			wp_register_style( $scheme, get_bloginfo('template_url') .'/css/style'. $color .'.css' );
			wp_enqueue_style( $scheme );
		}
		if ( $options['footercolor'] != '' ) {
			add_action('wp_head', 'progo_custom_footer_color', 1000 );
		}
	}
	do_action('progo_frontend_styles');
}
endif;
if ( ! function_exists( 'progo_custom_footer_color' ) ):
function progo_custom_footer_color() {
	$options = progo_get_theme_options();
	echo '<style type="text/css" id="custom_footer_color">#ftr, #ftr a { color: '. esc_attr($options['footercolor']) .' }</style>';
}
endif;
if ( ! function_exists( 'progo_reset_logo' ) ):
/**
 * wipe out any custom logo image setting
 * @since Business Pro 1.0
 */
function progo_reset_logo(){
	check_admin_referer( 'progo_reset_logo' );
	
	// reset logo settings
	$options = progo_get_theme_options();
	$options['logo'] = '';
	update_option( 'progo_options', $options );
	update_option( 'progo_settings_just_saved', 1 );
	
	wp_redirect( get_option('siteurl') .'/wp-admin/themes.php?page=theme_options' );
	exit();
}
endif;
if ( ! function_exists( 'progo_permalink_check' ) ):
/**
 * @since Business Pro 1.0
 */
function progo_permalink_check( $arg ){
	check_admin_referer( 'progo_permalink_check' );
	
	if ( $arg == 'recommended' ) {
		update_option( 'permalink_structure', '/%year%/%monthnum%/%day%/%postname%/' );
	} elseif ( $arg == 'default' ) {
		update_option( 'progo_permalink_checked', true );
	}
	wp_redirect( admin_url('options-permalink.php') );
	exit();
}
endif;
if ( ! function_exists( 'progo_menus_set' ) ):
/**
 * @since Business Pro 1.0
 */
function progo_menus_set(){
	check_admin_referer( 'progo_menus_set' );
	// menus are set - proceed to next step
	update_option( 'progo_businesspro_onstep', 7);
	
	wp_redirect( admin_url("themes.php?page=theme_options") );
	exit();
}
endif;
if ( ! function_exists( 'progo_firstform' ) ):
/**
 * @since Business Pro 1.0
 */
function progo_firstform(){
	check_admin_referer( 'progo_firstform' );
	// update first CF7 form to use on the Homepage area
	// NOTE : as of CF7 v3.0, they no longer have own table, just use CPT, so
	$firstform = get_posts( array(
				'numberposts'	=> 1,
				'post_type'		=> 'wpcf7_contact_form',
				'order'			=> 'ASC'
			));
	$firstformID = $firstform->ID;
	$hformID = wp_insert_post( array(
				'post_title' 	=>	'Homepage Form',
				'post_type' 	=>	'wpcf7_contact_form',
				'post_name'		=>	'homepage-form',
				'comment_status'=>	'open',
				'ping_status' 	=>	'open',
				'post_content' 	=>	'',
				'post_status' 	=>	'publish',
				'post_author' 	=>	1,
				'menu_order'	=>	0
			));
	
	$form = '<label for="name">Your Name<span title="Required">*</span></label>' . "\n"
		.'[text* name id:name class:text akismet:author]' . "\n\n"
		.'<label for="phone">Phone</label>' . "\n"
		.'[text phone id:phone class:text]' . "\n\n"
		.'<label for="email">Email<span title="Required">*</span></label>' . "\n"
		.'[email* email id:email class:text akismet:author_email]' . "\n\n"
		.'[submit class:button "Submit Today!"]';
	
	$subject = get_option( 'blogname' ) .' : Contact Form';
	$sender = '[name] <[email]>';
	$body = sprintf( __( 'Name: %s', 'wpcf7' ), '[name]' ) . "\n"
		. sprintf( __( 'Phone: %s', 'wpcf7' ), '[phone]' ) . "\n\n"
		. sprintf( __( 'Email: %s', 'wpcf7' ), '[email]' ) . "\n\n" . '--' . "\n"
		. sprintf( __( 'This mail is sent via contact form on %1$s %2$s', 'wpcf7' ),
			get_bloginfo( 'name' ), get_bloginfo( 'url' ) );
	$recipient = get_option( 'admin_email' );
	$additional_headers = '';
	$attachments = '';
	$use_html = 0;
	$mail = compact( 'subject', 'sender', 'body', 'recipient', 'additional_headers', 'attachments', 'use_html' );
	/*
	$wpdb->update( $wpcf7->contactforms,
		array(
			'title' => '',
			'form' => $form,
			'mail' => maybe_serialize( $mail )
		),
		array( 'cf7_unit_id' => 1 ),
		array( '%s', '%s', '%s' ),
		array( '%d' )
	);
	*/
	update_post_meta( $hformID, 'form', $form );
	update_post_meta( $hformID, 'mail', $mail );
	update_post_meta( $hformID, 'mail_2', get_post_meta( $firstformID, 'messages', true ) );
	update_post_meta( $hformID, 'messages', get_post_meta( $firstformID, 'messages', true ) );
	update_post_meta( $hformID, 'additional_settings', '' );
	update_option( 'progo_businesspro_onstep', 5);
	
	$opt = progo_get_theme_options();
	$opt['form'] = '[contact-form-7 id="'. $hformID .'"]';
	update_option( 'progo_options', $opt );
	
	wp_redirect( admin_url( 'admin.php?contactform='. $hformID .'&page=wpcf7' ) );
	exit();
}
endif;
if ( ! function_exists( 'progo_firstform_set' ) ):
/**
 * @since Business Pro 1.0
 */
function progo_firstform_set(){
	check_admin_referer( 'progo_firstform_set' );
	// first form is set - proceed to next step
	update_option( 'progo_businesspro_onstep', 6);
	
	wp_redirect( admin_url("options-permalink.php") );
	exit();
}
endif;
if ( ! function_exists( 'progo_arraytotop' ) ):
/**
 * helper function to bring a given element to the start of an array
 * @param parent array
 * @param element to bring to the top
 * @return sorted array
 * @since Business Pro 1.0
 */
function progo_arraytotop($arr, $totop) {
	// Backup and delete element from parent array
	$toparr = array($totop => $arr[$totop]);
	unset($arr[$totop]);
	// Merge the two arrays together so our widget is at the beginning
	return array_merge( $toparr, $arr );
}
endif;
if ( ! function_exists( 'progo_save_meta' ) ):
/**
 * hooked to 'save_post' by add_action in progo_setup()
 * checks for _progo (direct) meta data, and performs validation & sanitization
 * @param post_id to check meta on
 * @return post_id
 * @since Business Pro 1.0
 */
function progo_save_meta( $post_id ){
	// verify if this is an auto save routine. If it is,
	// our form has not been submitted, so we don't want to do anything
	if ( defined('DOING_AUTOSAVE') && DOING_AUTOSAVE ) { 
		return $post_id;
	}
	// check permissions
	if ( isset( $_POST['post_type'] ) ) {
		switch( $_POST['post_type'] ) {
			case 'page':
				if ( current_user_can( 'edit_page', $post_id ) ) {
					// OK, we're authenticated: we need to find and save the data
					if ( isset( $_POST['_progo_sidebar'] ) ) {
						$sidebar = $_POST['_progo_sidebar'];
						
						if ( in_array ( $sidebar, array('main', 'home', 'blog', 'contact') ) ) {
							update_post_meta($post_id, "_progo_sidebar", $sidebar);
							return $sidebar;
						}
					}
				}
				break;
			case 'progo_homeslide':
				if ( current_user_can( 'edit_page', $post_id ) ) {
					// OK, we're authenticated: we need to find and save the data
					/*
					if ( isset( $_POST['progo_slidecontent'] ) ) {
						$slidecontent = $_POST['progo_slidecontent'];
						$slidecontent['textcolor'] = $slidecontent['textcolor'] == 'Light' ? 'Light' : 'Dark';
						$slidecontent['showtitle'] = $slidecontent['showtitle'] == 'Show' ? 'Show' : 'Hide';
						
						update_post_meta($post_id, "_progo_slidecontent", $slidecontent);
						return $slidecontent;
						
					}
					*/
				}
				break;
				
			case 'progo_facebooktabs':	
				if(isset($_POST['FB_Tab_meta_form'])){
					//save metadata
					update_post_meta($post_id, '_FB_Tab_meta_form', ($_POST['FB_Tab_meta_form']));
				}
				if(isset($_POST['FB_Tab_meta_template'])){
					//save metadata
					update_post_meta($post_id, '_FB_Tab_meta_template', ($_POST['FB_Tab_meta_template']));
				}
				
				if(isset($_POST['FB_Tab_meta_video'])){
					//save metadata
					update_post_meta($post_id, '_FB_Tab_meta_video', ($_POST['FB_Tab_meta_video']));
				}
				if(isset($_POST['FB_Tab_meta_thumb'])){
					//save metadata
					update_post_meta($post_id, '_FB_Tab_meta_thumb', ($_POST['FB_Tab_meta_thumb']));
				}
				if(isset($_POST['FB_Tab_meta_headline'])){
					//save metadata
					update_post_meta($post_id, '_FB_Tab_meta_headline', ($_POST['FB_Tab_meta_headline']));
				}
				$bullets = array(
					1 => isset($_POST['fb-bullet-1']) ? $_POST['fb-bullet-1'] : '',
					2 => isset($_POST['fb-bullet-2']) ? $_POST['fb-bullet-2'] : '',
					3 => isset($_POST['fb-bullet-3']) ? $_POST['fb-bullet-3'] : '',
					4 => isset($_POST['fb-bullet-4']) ? $_POST['fb-bullet-4'] : '',
					'image' => isset($_POST['fb-bullet-image']) ? $_POST['fb-bullet-image'] : ''
				);
				update_post_meta($post_id, '_FB_BULLET', $bullets);
				$upd = isset($_POST['fb_pre_headline']) ? $_POST['fb_pre_headline'] : '';
				update_post_meta($post_id, '_FB_PRE_HEADLINE', $upd);
				$upd = isset($_POST['fb_sub_headline']) ? $_POST['fb_sub_headline'] : '';
				update_post_meta($post_id, '_FB_SUB_HEADLINE', $upd);
				$upd = isset($_POST['fb_feture_img']) ? $_POST['fb_feture_img'] : '';
				update_post_meta($post_id, '_FB_FEATURE_IMG', $upd);
				
				break;
			case 'progo_ppc':
				if(isset($_POST['PPC_meta_template'])){
					//save metadata
					update_post_meta($post_id, '_PPC_meta_template', ($_POST['PPC_meta_template']));
				}
				if(isset($_POST['ppc_form_title'])){
					//save metadata
					update_post_meta($post_id, '_ppc_form_title', ($_POST['ppc_form_title']));
				}
				if(isset($_POST['ppc_form_shortcode'])){
					//save metadata
					update_post_meta($post_id, '_ppc_form_shortcode', ($_POST['ppc_form_shortcode']));
				}
				if(isset($_POST['ppcformcontent'])){
					//save metadata
					update_post_meta($post_id, '_ppcformcontent', ($_POST['ppcformcontent']));
				}
				break;
		}
	}
	return $post_id;
}
endif;
if ( ! function_exists( 'progo_colorscheme_switch' ) ):
/**
 * helper function to switch the current Color Scheme
 * @since Business Pro 1.0
 */
function progo_colorscheme_switch( $color ) {
	$okgo = true;
	$avail = progo_colorschemes();
	if( current_user_can('manage_options') == false ) {
		$okgo = false;
	} elseif ( in_array($color, $avail) == false ) {
		$okgo = false;
	}
	
	if ( $okgo == true ) {
		$opt = progo_get_theme_options();
		$opt[colorscheme] = $color;
		
		// check to update footer text & link color too
		if ( $opt['footercolor'] == '' ) {
			if ( in_array( $color, array( 'BlackOrange', 'DarkGreen', 'GreenBrown' ) ) ) {
				$opt['footercolor'] = 'fff';
			}
		}
		
		update_option( 'progo_options', $opt );
		
		wp_redirect( get_option('siteurl') );
	} else {
		wp_die('Nice try...');
		return;
	}
}
endif;
/**
 * ProGo Site Settings Options defaults
 * @since Business Pro 1.0
 */
function progo_options_defaults() {
	// Define default option settings
	$tmp = progo_get_theme_options();
    if ( ! is_array( $tmp ) ) {
		$def = progo_get_default_theme_options();
		update_option( 'progo_options', $def );
	}
	$tmp = get_option( 'progo_slides' );
    if ( ! is_array( $tmp ) ) {
		$def = array('count'=>0);
		update_option( 'progo_slides', $def );
	}
	
	update_option( 'progo_businesspro_installed', true );
	update_option( 'progo_businesspro_apikey', '' );
	update_option( 'progo_businesspro_apiauth', '100' );
	
	update_option( 'wpsc_ignore_theme', true );
	
	// set large image size
	update_option( 'large_size_w', 650 );
	update_option( 'large_size_h', 413 );
	
	// no SHARETHIS automatically all over the place?
	update_option( 'st_add_to_content', 'no' );
	update_option( 'st_add_to_page', 'no' );
	
	// how about setting widgets?
	$ourwidgets = wp_get_sidebars_widgets();
	foreach ( $ourwidgets as $a => $w ) {
		switch( $a ) {
			case 'blog':
				if ( count($w) == 6 ) {
					// default so many widgets, clean up plz
					$newblogw = array();
					foreach ( $w as $k => $v ) {
						$lastd = strrpos( $v, '-' );
						$wbase = substr( $v, 0, $lastd );
						if ( !in_array($wbase, array( 'search', 'recent-comments', 'meta' ) ) ) {
							$newblogw[] = $v;
						}
					}
					$ourwidgets[$a] = $newblogw;
				}
				break;
			case 'home':
				if ( count($w) == 0 ) {
					$newhomew = array(
						'progo-office-info-2',
						'progo-share-2',
					);
					$ourwidgets[$a] = $newhomew;
				}
				break;
		}
	}
	
	wp_set_sidebars_widgets($ourwidgets);
}

/********* more helper functions *********/

if ( ! function_exists( 'progo_bodyclasses' ) ):
/**
 * adds some additional classes to the <body> based on what page we're on
 * @param array of classes to add to the <body> tag
 * @since Business Pro 1.0
 */
function progo_bodyclasses($classes) {
	switch ( get_post_type() ) {
		case 'post':
			$classes[] = 'blog';
			break;
	}
	if ( is_front_page() ) {
		$options = progo_get_theme_options();
	}
	// add another class to body if we have a custom bg image
	if ( get_background_image() != '' ) {
		$classes[] = 'custombg';
	}
	
	return $classes;
}
endif;
if ( ! function_exists( 'progo_menufilter' ) ):
/**
 * adds some additional classes to Menu Items
 * so we can mark active menu trails easier
 * @param array of classes to add to the <body> tag
 * @since Business Pro 1.0
 */
function progo_menufilter($items, $args) {
	$blogID = get_option('progo_blog_id');
	foreach ( $items as $i ) {
		if ( $i->object_id == $blogID ) {
			$i->classes[] = 'blog';
		}
	}
	// want our MAINMENU to have MAX of 7 items
	if ( $args->theme_location == 'mainmenu' ) {
		$toplinks = 0;
		foreach ( $items as $k => $v ) {
			if ( $v->menu_item_parent == 0 ) {
				$toplinks++;
			}
			if ( $toplinks > 7 ) {
				unset($items[$k]);
			}
		}
	}
	return $items;
}
endif;
if ( ! function_exists( 'progo_businesspro_completeness' ) ):
/**
 * check which step / % complete current site is at
 * @since Business Pro 1.0
 */
function progo_businesspro_completeness( $onstep ) {
	if ( $onstep < 1 || $onstep > 7 ) {
		$onstep = 1;
	}
	
	if ( $onstep < 7 ) { // ok check it
		switch($onstep) {
			case 1: // check API auth
				$apiauth = get_option( 'progo_businesspro_apiauth', true );
				if( $apiauth == '100' ) {
					$onstep = 2;
				}
				break;
			case 2: // CF7 INSTALLED
				$plugs = get_plugins();
				if( isset( $plugs['contact-form-7/wp-contact-form-7.php'] ) == true ) {
					$onstep = 3;
				}
				break;
			case 3: // CF7 ACTIVATED
				if ( is_plugin_active( 'contact-form-7/wp-contact-form-7.php' ) ) {
					$onstep = 4;
				}
				break;
			case 6: // Permalinks
				$permalink = get_option( 'permalink_structure', '' );
				$defaultok = get_option( 'progo_permalink_checked', false );
				if ( ( $permalink != '' ) || ( ( $permalink == '' ) &&  ( $defaultok == true ) ) ) {
					$onstep = 7;
				}
				break;
		}
	}
	return $onstep;
}
endif;
/**
 * hooked to 'admin_notices' by add_action in progo_setup()
 * used to display "Settings updated" message after Site Settings page has been saved
 * @uses get_option() To check if our Site Settings were just saved.
 * @uses update_option() To save the setting to only show the message once.
 * @since Business Pro 1.0
 */
function progo_admin_notices() {
	global $pagenow;
	// api auth check
	$apiauth = get_option( 'progo_businesspro_apiauth', true );
	if( $apiauth != '100' ) {
	?>
	<div class="error">
		<p><?php
        switch($apiauth) {
			case 'new':	// key has not been entered yet
				echo '<a href="themes.php?page=theme_options" title="Site Settings">Please enter your ProGo Themes API Key to Activate your theme.</a>';
				break;
			case '999': // invalid key?
				echo 'Your ProGo Themes API Key appears to be invalid. <a href="themes.php?page=theme_options" title="Site Settings">Please double check it.</a>';
				break;
			case '300': // wrong site URL?
				echo '<a href="themes.php?page=theme_options" title="Site Settings">The ProGo Themes API Key you entered</a> is already bound to another URL.';
				break;
		}
		?></p>
	</div>
<?php
	}
	
	if( get_option('progo_settings_just_saved')==true ) { ?>
	<div class="updated fade">
		<p>Settings updated. <a href="<?php bloginfo('url'); ?>/">View site</a></p>
	</div>
<?php
		update_option('progo_settings_just_saved',false);
	}
	
	$onstep = absint(get_option('progo_businesspro_onstep', true));
	
	if ( $onstep < 7 ) {
		$onstep = progo_businesspro_completeness( $onstep );
		update_option( 'progo_businesspro_onstep', $onstep);
		// couldnt check step 2 before but now we have get_plugins() function
		if ( $onstep == 2 ) {
			if ( isset( $_REQUEST['action'] ) ) {
				if ( $_REQUEST['action'] == 'install-plugin' ) {
					return;
				}
			}
		}
		// quick check if the ACTIVATE link was just clicked...
		if ( ( $onstep == 3 ) && is_plugin_active( 'contact-form-7/wp-contact-form-7.php' ) ) {
			$onstep = 4;
			update_option( 'progo_businesspro_onstep', $onstep);
		}
		
		echo '<div class="updated progo-steps">';
		$pct = 0;
		$nst = '';
		switch($onstep) {
			case 1: // theme has been activated but no good API key yet
				$pct = 15;
				$nst = 'Activate your ProGo Theme API Key';
				break;
			case 2: // INSTALL CF7
				$lnk = ( function_exists( 'wp_nonce_url' ) ) ? wp_nonce_url( 'update.php?action=install-plugin&amp;plugin=contact-form-7', 'install-plugin_contact-form-7' ) : 'plugin-install.php';
				$pct = 35;
				$nst = '<a href="'. esc_url( $lnk ) .'">Click Here to Install the Contact Form 7 Plugin</a>';
				break;
			case 3: // ACTIVATE CF7
				$lnk = ( function_exists( 'wp_nonce_url' ) ) ? wp_nonce_url( 'plugins.php?action=activate&amp;plugin=contact-form-7/wp-contact-form-7.php', 'activate-plugin_contact-form-7/wp-contact-form-7.php' ) : 'plugins.php';
				$pct = 50;
				$nst = '<a href="'. esc_url( $lnk ) .'">Click Here to Activate the Contact Form 7 Plugin</a>';
				break;
			case 4: // CREATE CF7 Form1
				$pct = 65;
				$nst = 'The CF7 Plugin is Installed &amp; Activated! <a href="'. wp_nonce_url("admin.php?progo_admin_action=firstform", 'progo_firstform') .'">Click Here to set up the first Form for your Homepage</a>.';
				break;
			case 5: // Customize further
				$pct = 75;
				$nst = 'When you are done configuring your first Contact Form, <a href="'. wp_nonce_url("admin.php?progo_admin_action=firstform_set", 'progo_firstform_set') .'">click here to proceed to the next step</a>.';
				break;
			case 6: // Permalinks
				$pct = 80;
				$nst = 'Your <em>Permalinks</em> settings are still set to the Default option. <a href="'. wp_nonce_url("admin.php?progo_admin_action=permalink_recommended", 'progo_permalink_check') .'">Use the ProGo-Recommended "Day and name" setting</a>, <a href="'. admin_url("options-permalink.php") .'">Choose another non-Default option for yourself</a>, or <a href="'. wp_nonce_url("admin.php?progo_admin_action=permalink_default", 'progo_permalink_check') .'">keep the Default setting and proceed to the next step</a>.';
				break;
			case 7: // Main Menu
				$pct = 90;
				$nst = '<a href="'. admin_url('nav-menus.php') .'">Customize your site\'s Menus</a> by adding more links from the left column, and rearranging links with Drag-n-Drop. <strong>This is the last step to setting up your Business Pro site!</strong> When your Menus are set, <a href="'. wp_nonce_url("admin.php?progo_admin_action=menus_set", 'progo_menus_set') .'">click here to remove this message</a>.';
				break;
		}
		echo '<p>Your ProGo Business Pro site is <strong>'. $pct .'% Complete</strong> - Next Step: '. $nst .'</p></div>';
	}
}

/**
 * hooked to 'site_transient_update_themes' by add_filter in progo_setup()
 * checks ProGo-specific URL to see if our theme is up to date!
 * @param array of checked Themes
 * @uses wp_get_themes() To retrieve list of all installed themes.
 * @uses wp_remote_post() To check remote URL for updates.
 * @return checked data array
 * @since Business Pro 1.0
 */
function progo_update_check($data) {
	if ( is_admin() == false ) {
		return $data;
	}
	
	$themes = wp_get_themes( array( 'allowed' => true ) );
	
	if ( isset( $data->checked ) == false ) {
		$checked = array();
		// fill CHECKED array - not sure if this is necessary for all but doesnt take a long time?
		foreach ( $themes as $thm ) {
			// we don't care to check CHILD themes
			if( $thm['Parent Theme'] == '') {
				$checked[$thm['Template']] = $thm['Version'];
			}
		}
		$data->checked = $checked;
	}
	if ( isset( $data->response ) == false ) {
		$data->response = array();
	}
	
	$request = array(
		'slug' => "businesspro",
		'version' => $data->checked['businesspro'],
		'siteurl' => get_bloginfo('url')
	);
	
	// Start checking for an update
	global $wp_version;
	$apikey = get_option('progo_businesspro_apikey',true);
	if ( $apikey != '' ) {
		$apikey = substr( strtolower( str_replace( '-', '', $apikey ) ), 0, 32);
	}
	$checkplz = array(
		'body' => array(
			'action' => 'theme_update', 
			'request' => serialize($request),
			'api-key' => $apikey
		),
		'user-agent' => 'WordPress/'. $wp_version .'; '. get_bloginfo('url')
	);

	$raw_response = wp_remote_post('http://www.progo.com/updatecheck/', $checkplz);
	
	if ( ( ! is_wp_error( $raw_response ) ) && ( $raw_response['response']['code'] == 200 ) )
		$response = unserialize($raw_response['body']);
		
	if ( ! empty( $response ) ) {
		// got response back. check authcode
		// wp_die('response:<br /><pre>'. print_r($response,true) .'</pre><br /><br />apikey: '. $apikey );
		// only save AUTHCODE if APIKEY is not blank.
		if ( $apikey != '' ) {
			update_option( 'progo_businesspro_apiauth', $response['authcode'] );
		} else {
			update_option( 'progo_businesspro_apiauth', 'new' );
		}
		if ( version_compare($data->checked['businesspro'], $response['new_version'], '<') ) {
			$data->response['businesspro'] = array(
				'new_version' => $response['new_version'],
				'url' => $response['url'],
				'package' => $response['package']
			);
		}
	}
	
	return $data;
}

function progo_to_twentyten() {
	$brickit = true;
	global $wp_query;
	// check for PREVIEW theme
	if ( isset( $wp_query->query_vars['preview'] ) ) {
		if ( $wp_query->query_vars['preview'] == 1 ) {
			$brickit = false;
		}
	}
	if ( $brickit === true ) {
		$msg = 'This ProGo Themes site is currently not Activated.';
		
		if(current_user_can('edit_pages')) {
			$msg .= '<br /><br /><a href="'. trailingslashit(get_bloginfo('url')) .'wp-admin/themes.php?page=theme_options">Click here to update your API Key</a>';
		}
		wp_die($msg);
	}
}
if ( ! function_exists( 'progo_admin_post_thumbnail_html' ) ):
/**
 * hooked by add_filter to 'admin_post_thumbnail_html'
 * @since Business Pro 1.0
 */
function progo_admin_post_thumbnail_html($html) {
	global $post_type;
	global $post;
	if( $post_type=='progo_homeslide' ) {
		$options = progo_get_theme_options();
		switch( $options['layout'] ) {
			default:
				$size = '960px W x 445px H';
				break;
		}
		$html = str_replace(__('Set featured image').'</a>',__('Upload/Select a Background Image') .'</a> '. __('Recommended Size') .': '. $size, $html );
	}
	return $html;
}
endif;
/**
 * hooked by add_filter to 'wpseo_admin_bar_menu'
 * to tweak the new WP 3.1 ADMIN BAR
 * @since Business Pro 1.0
 */
function progo_admin_bar_menu() {
	global $wp_admin_bar;
	
	$wp_admin_bar->remove_menu('widgets');
	$wp_admin_bar->add_menu( array( 'id' => 'progo', 'title' => __('ProGo Theme'), 'href' => admin_url('themes.php?page=theme_options'), ) );
	// move Appearance > Widgets & Menus submenus to below our new ones
	$wp_admin_bar->remove_menu('widgets');
	$wp_admin_bar->remove_menu('menus');
	$wp_admin_bar->add_menu( array( 'parent' => 'progo', 'id' => 'progothemeoptions', 'title' => __('Theme Options'), 'href' => admin_url('themes.php?page=theme_options') ) );
	$wp_admin_bar->add_menu( array( 'parent' => 'progo', 'id' => 'homeslides', 'title' => __('Homepage Slides'), 'href' => admin_url('edit.php?post_type=progo_homeslide') ) );
	$wp_admin_bar->add_menu( array( 'parent' => 'progo', 'id' => 'background', 'title' => __('Background'), 'href' => admin_url('themes.php?page=custom-background') ) );
	$wp_admin_bar->add_menu( array( 'parent' => 'progo', 'id' => 'widgets', 'title' => __('Widgets'), 'href' => admin_url('widgets.php') ) );
	$wp_admin_bar->add_menu( array( 'parent' => 'progo', 'id' => 'menus', 'title' => __('Menus'), 'href' => admin_url('nav-menus.php') ) );
	
	$avail = progo_colorschemes();
	if ( count($avail) > 0 ) {
		$wp_admin_bar->add_menu( array( 'parent' => 'progo', 'id' => 'progo_colorscheme', 'title' => 'Color Scheme', 'href' => admin_url('themes.php?page=theme_options') ) );
	}
	foreach($avail as $color) {
		$wp_admin_bar->add_menu( array( 'parent' => 'progo_colorscheme', 'id' => 'progo_colorscheme'.esc_attr($color), 'title' => esc_attr($color), 'href' => admin_url('admin.php?progo_admin_action=color'. esc_attr($color) ) ) );
	}
}

if ( ! function_exists( 'progo_mail_content_type' ) ):
function progo_mail_content_type( $content_type ) {
	return 'text/html';
}
endif;
if ( ! function_exists( 'progo_oembed_fix' ) ):
function progo_oembed_fix($oembvideo, $url, $attr) {
	if(strpos($url,'youtube.com')!== false) {
		$patterns = array();
		$replacements = array();
		$patterns[] = '/<embed/';
		$patterns[] = '/allowscriptaccess="always"/';
		$patterns[] = '/feature=oembed/';
		
		$replacements[] = '<param name="wmode" value="opaque" /><embed';
		$replacements[] = 'wmode="opaque" allowscriptaccess="always"';
		$replacements[] = 'feature=oembed&amp;wmode=opaque';
		
		return preg_replace($patterns, $replacements, $oembvideo);
	}
	
	return $oembvideo;
}
endif;
if ( ! function_exists( 'progo_nomenu_cb' ) ):
function progo_nomenu_cb() {
	return '<ul></ul>';
}
endif;


//DND PPC DKI Magic --- Begin

if ( !function_exists( 'progo_ppc_kw' ) ):
function progo_ppc_kw ($location){
	$uri = progo_get_kw_uri();
	$index = array_search("ppc/",$uri);
	$out_index1 = (1 + $index);
	$out_index2 = (2 + $index);
	if ( isset($uri[1]) ) {
	$kwy1 = str_replace('/','',$uri[$out_index1]);
	$kwy1 = str_replace('-',' ',$kwy1);
	} else {
		$kwy1 = '';
	}
	if ( isset($uri[2]) ) {
	$kwy2 = str_replace('/','',$uri[$out_index2]);
	$kwy2 = str_replace('-',' ',$kwy2);
	} else {
		$kwy2 = '';
	}
	switch($location['loc']){
		case '2':
			$keyword = $kwy2;
			break;
		default:
			$keyword = $kwy1;
			break;
	}
	
	return $keyword;
}
add_shortcode('keyword', 'progo_ppc_kw');
endif;
if ( !function_exists( 'progo_get_kw_uri' ) ):
/* from URL like /ppc/test-page/ , returns array('ppc/','test-page/') */
function progo_get_kw_uri() {
        $request_uri = $_SERVER['REQUEST_URI'];
        // for consistency, check to see if trailing slash exists in URI request
        if (substr($request_uri, -1)!="/") {
                $request_uri = $request_uri."/";
        }
        preg_match_all('#[^/]+/#', $request_uri, $matches);
        // could've used explode() above, but this is more consistent across varied WP installs
        $uri = $matches[0];
        return $uri;
}
endif;
if ( !function_exists( 'progo_ppc_title' ) ):
function progo_ppc_title (){
	$uri = progo_get_kw_uri();
	$index = array_search("ppc/",$uri);
	$out_index1 = (1 + $index);
	$out_index2 = (2 + $index);
	
	if ( isset($uri[1]) ) {
	$kwy1 = str_replace('/','',$uri[$out_index1]);
	$kwy1 = str_replace('-',' ',$kwy1);
	} else {
		$kwy1 = '';
	}
	if ( isset($uri[2]) ) {
	$kwy2 = str_replace('/','',$uri[$out_index2]);
	$kwy2 = str_replace('-',' ',$kwy2);
	} else {
		$kwy2 = '';
	}
	//$title_out = $kwy1 . ( ($kwy2 != "") ? ' | '. $kwy2 : '' );
	
	$title_out = ( ($kwy2 != "") ?  $kwy2 : $kwy1 );
	
	echo ucwords($title_out);
}
endif;
if ( !function_exists( 'progo_populate_ppc_content' ) ):
function progo_populate_ppc_content(){
	global $post;
	$uri = progo_get_kw_uri();
	$index = array_search("ppc/",$uri);
	$out_index1 = (1 + $index);
	$the_slug = str_replace('/','',$uri[$out_index1]);
	$args=array(
		'name' => $the_slug,
		'post_type' => 'progo_ppc',
		'post_status' => 'publish',
		'showposts' => 1
	);
	
	$lastposts = get_posts( $args );
	
	/*
	foreach($lastposts as $post) {
		setup_postdata($post); 
		return the_content(); 
	}
	echo $the_slug;
	echo $uri[2];
	
	*/
	
	if ( count($lastposts) > 0 ) {
		$post = $lastposts[0];
		setup_postdata($post); 
		return the_content();
		  
	} else {
		echo 'ktjljklj;lj';
	}
}
endif;

if ( !function_exists( 'progo_ppc_get_id' ) ):
function progo_ppc_get_id(){
	$uri = progo_get_kw_uri();
	$index = array_search("ppc/",$uri);
	$out_index1 = (1 + $index);
	$the_slug = str_replace('/','',$uri[$out_index1]);
	$args=array(
		'name' => $the_slug,
		'post_type' => 'progo_ppc',
		'post_status' => 'publish',
		'showposts' => 1
	);
	
	$lastposts = get_posts( $args );
	$post_id = 0;
	foreach($lastposts as $post) {
		setup_postdata($post); 
		$post_id = $post->ID;
	}
	return $post_id;
}
endif;

add_filter( 'template_redirect', 'progo_ppc_template' );
remove_filter('template_redirect','redirect_canonical');

if ( !function_exists( 'progo_ppc_template' ) ):
function progo_ppc_template() {
	global $post; 
	$uri = progo_get_kw_uri();
	$index = array_search("ppc/",$uri);
	
	if ( $index === false ) {
		return;
	}
	
	$theSlug = str_replace("/", "", $uri[($index+1)]);
	
	if ($uri[$index]=='ppc/') {
		
	
	$args=array(
  	'name' => $theSlug,
  	'post_type' => 'progo_ppc',
  	'post_status' => 'publish',
  	'showposts' => 1,
  	'ignore_sticky_posts'=> 1
	);
	
$my_posts = get_posts($args);

if( $my_posts ) {
$postID = $my_posts[0]->ID;
$PPC_meta_template = get_post_meta($postID, '_PPC_meta_template', true);
}


		switch ($PPC_meta_template) {
		case 2:
  			$single_template = dirname( __FILE__ ) . '/PPC-page2.php';
  		break;
		/*
		case 3:
  		$single_template = dirname( __FILE__ ) . '/PPC-page2.php';
 		break;
 		case 4:
  		$single_template = dirname( __FILE__ ) . '/PPC-page4.php';
 		break;
		*/
       default:
       $single_template = dirname( __FILE__ ) . '/PPC-page.php';
		}

		status_header(200);
		include($single_template);
		die();
	}
}
endif;


add_action( 'add_meta_boxes', 'fb_bullet' );



function fb_bullet() {
	global $post;
    add_meta_box(
        'custom_menu_meta_box',
        'Bullets', 
        'add_fb_bullet',
        'progo_facebooktabs',
		'normal',
		'default'
    );
	
}



function add_fb_bullet( $post ) {
	$FB_Tab_meta_template = get_post_meta($post->ID, '_FB_Tab_meta_template', true);
	
	$fb_bullets = get_post_meta($post->ID, '_FB_BULLET', true);
	if ( $fb_bullets == '' ) {
		$fb_bullets = array(
			1 => '',
			2 => '',
			3 => '',
			4 => '',
			'image' => ''
		);
	}
	?>
	<table id="bullets" >
		<tr>
			<td>Bullet №1</td>
			<td><input type="text" name="fb-bullet-1" size="70" value="<?php echo $fb_bullets[1] ?>" /></td>
		</tr>
		<tr>
			<td>Bullet №2</td>
			<td><input type="text" name="fb-bullet-2" size="70" value="<?php echo $fb_bullets[2] ?>" /></td>
		</tr>
		<tr>
			<td>Bullet №3</td>
			<td><input type="text" name="fb-bullet-3" size="70" value="<?php echo $fb_bullets[3] ?>" /></td>
		</tr>
		<tr>
			<td>Bullet №4</td>
			<td><input type="text" name="fb-bullet-4" size="70" value="<?php echo $fb_bullets[4] ?>" /></td>
		</tr>
		<tr>
			<td>Bullets Image</td>
			<td><input type="text" name="fb-bullet-image" size="50" value="<?php echo $fb_bullets['image'] ?>" /></td>
		</tr>
	</table>
	<?php
	
}


function add_fb_pre_headline( $post ) {
	$fb_pre_headline = get_post_meta($post->ID, '_FB_PRE_HEADLINE', true);
	?>
	<input type="text" name="fb_pre_headline" value="<?php echo $fb_pre_headline; ?>" size="75" />
	<?php
	
}
function add_fb_sub_headline( $post ){
	$fb_sub_headline = get_post_meta($post->ID, '_FB_SUB_HEADLINE', true);
	?>
	<input type="text" name="fb_sub_headline"  value="<?php echo $fb_sub_headline; ?>" size="75" />
	<?php
}

function FB_featured_image( $post ){
 $fb_feature_img = get_post_meta($post->ID, '_FB_FEATURE_IMG', true);
	?>
	<input type="text" name="fb_feture_img"  value="<?php echo $fb_feature_img; ?>" size="75" />
	<?php
}


function save_fb_pre_headline( $post_id ) {
  if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) 
      return;

  if ( 'page' == $_POST['post_type'] ) 
  {
    if ( !current_user_can( 'edit_page', $post_id ) )
        return;
  }
  else
  {
    if ( !current_user_can( 'edit_post', $post_id ) )
        return;
  }
	
}



//DND PPC DKI Magic --- End

add_shortcode('smbcallout', 'progo_smbcallout');
function progo_smbcallout( $atts, $content = null ) {
	extract( shortcode_atts( array(
		'headline' => 'The Headline from your Call to Action shortcode appeared to be missing!',
		'lnk' => '#',
		'call' => false,
		'class' => 'button',
	), $atts ) );
	$oot = '<div class="callout grid_8"><h2>'. str_replace('|', '<br />', esc_attr( $headline ) ) .'</h2>';
	if ( $call != false ) {
		$oot .= '<a href="'. esc_url($lnk) .'" class="'. esc_attr($class) .'">'. esc_attr($call) .'</a>';
	}
	$oot .= '</div>';
	return $oot;
}

add_shortcode('smb2col', 'progo_smb2cols');
function progo_smb2cols( $atts, $content = null ) {
	$content = str_replace( '<p></p>', '', force_balance_tags( $content ) );
	
	$oot = '<div class="grid_8 alpha omega"><div class="grid_4 alpha">';
	$oot .= str_replace('<p>[smbcol]</p>', '</div><div class="grid_4 alpha omega">', $content);
	$oot .= '</div></div>';
	return $oot;
}