<?php
/**
 * The Header for our theme.
 *
 * Displays all of the <head> section and everything up till <div id="main">
 *
 * @package ProGo
 * @subpackage SmallBusiness
 * @since SmallBusiness 1.0
 */
 
header("HTTP/1.0 200 OK");
global $wp_query;
status_header( 200 );
$wp_query->is_404=false;
?><!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
<meta charset="<?php bloginfo( 'charset' ); ?>" />
<title><?php
	progo_ppc_title(); 
	echo " | ";
	bloginfo( 'name' );
		?></title>
<link rel="profile" href="http://gmpg.org/xfn/11" />
<link rel="stylesheet" type="text/css" media="all" href="<?php bloginfo( 'stylesheet_url' ); ?>" />
<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>" />
<link rel="stylesheet" type="text/css" media="all" href="<?php bloginfo( 'stylesheet_directory' ); ?>/css/ppc.css" />

<?php wp_head(); ?>
</head>

<body class="ppc">
<div id="fx">
    <div id="hdrap">
        <div id="hdr" class="container_12">
        	<div class="grid_8">
            <?php progo_sitelogo();
            $options = get_option( 'progo_options' );
            if ( (int) $options['showdesc'] == 1 ) { ?>
            <div id="slogan"><?php bloginfo( 'description' ); ?></div>
            <?php } ?>
            </div>
             <?php get_sidebar('header');
			$menuclass = 'menu';
			if ( $options['menuwidth'] == 'auto' ) $menuclass .= ' autow';
			wp_nav_menu( array('container' => 'false', 'theme_location' => 'ppcmenu', 'menu_id' => 'nav', 'menu_class' => $menuclass, 'fallback_cb' => 'progo_nav_fallback' )); ?>
        </div>
    </div><!-- #hdrwrap -->