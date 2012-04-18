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
?><!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
<meta charset="<?php bloginfo( 'charset' ); ?>" />
<title><?php
	ppc_title(); 
	echo " | ";
	bloginfo( 'name' );
		
		?></title></title>
<link rel="profile" href="http://gmpg.org/xfn/11" />
<link rel="stylesheet" type="text/css" media="all" href="<?php bloginfo( 'stylesheet_url' ); ?>" />
<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>" />


<style type="text/css" media="all">
#ppc-sidebar {
		margin-top:15px;
	}
	
	#ppc-content {
		margin-top:15px;
	}

</style>

<?php wp_head(); ?>
</head>

<body <?php body_class(); ?>><div id="fx">
<div id="wrap" class="container_12">
	<div id="page" class="container_12">
        <div id="hdr" class="container_12">
        	<div class="grid_6">
            <?php progo_sitelogo();
            $options = get_option( 'progo_options' );
            if ( (int) $options['showdesc'] == 1 ) { ?>
            <div id="slogan"><?php bloginfo( 'description' ); ?></div>
            <?php } ?>
            </div>
            <?php wp_nav_menu( array('menu' => 'PPC Menu', 'theme_location' => 'ppc-primary', 'menu_id' => 'nav' )); ?>
        </div>
