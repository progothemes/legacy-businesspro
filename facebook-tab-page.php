<?php
/**
 * Template Name Posts: Facebook Tab Page
 * @package ProGo
 * @subpackage Ecommerce
 * @since Direct 1.0
 */

?>
<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
<meta charset="<?php bloginfo( 'charset' ); ?>" />
<meta name="description" content="<?php bloginfo('description'); ?>" />
<title><?php
    wp_title( '' );
    
    ?></title>

<link rel="stylesheet" type="text/css" media="all" href="<?php bloginfo( 'stylesheet_url' ); ?>" />

</head>

<body class="container_12" style="background:#fff;">
<div id="fb-root"></div>
<script>
(function(d, s, id) {
  var js, fjs = d.getElementsByTagName(s)[0];
  if (d.getElementById(id)) {return;}
  js = d.createElement(s); js.id = id;
  js.src = "//connect.facebook.net/en_US/all.js#xfbml=1";
  fjs.parentNode.insertBefore(js, fjs);
}(document, 'script', 'facebook-jssdk'));</script>
	    <div class="grid_7">
		    <div>
			    <?php if ( have_posts() ) while ( have_posts() ) : the_post(); ?>
				 		            
                    <div class="content"><?php the_content(); ?></div>				            
                    			        <?php endwhile; // end of the loop. ?>
				</div>
	    </div><!-- #main -->




<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.6.1/jquery.min.js"></script>

</body>
</html>
