<?php
/**
 * Template Name Posts: Facebook Tab Page
 * @package ProGo
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
<link rel="stylesheet" type="text/css" media="all" href="<?php bloginfo( 'stylesheet_directory' ); ?>/css/facebook-tabs.css" />
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.6.1/jquery.min.js"></script>

</head>

<body class="container_12 page_3 fb" style="background:#fff;">

<script>
(function(d, s, id) {
  var js, fjs = d.getElementsByTagName(s)[0];
  if (d.getElementById(id)) {return;}
  js = d.createElement(s); js.id = id;
  js.src = "//connect.facebook.net/en_US/all.js#xfbml=1";
  fjs.parentNode.insertBefore(js, fjs);
}(document, 'script', 'facebook-jssdk'));
$(document).ready(function(){  $('.cstm_buttons input[type="submit"]').each(function(){ $(this).val(''); $(this).addClass('cstm_but'); }); });
</script>
<div class="fb-general" >	
	<?php if ( have_posts() ) while ( have_posts() ) : the_post(); ?>
						<span class="fb-old-headline">
							<h2>
								<?php
									$FB_Tab_meta_headline = get_post_meta($post->ID, '_FB_Tab_meta_headline', true);
									print_r($FB_Tab_meta_headline);
								?>
							</h2>
						</span>	
						<span class="fb-headline" >
							<?php 
								$FB_pre_headline = get_post_meta($post->ID, '_FB_SUB_HEADLINE', true);
								print_r($FB_pre_headline);
							?>
						</span>
						<!--<div id="container">Loading the player ...</div>-->
						<div class="fb-templ3-text cstm_buttons">
							<?php
								$FBTabForm = get_post_meta($post->ID, '_FB_Tab_meta_form', true);
								echo apply_filters('the_content', $FBTabForm);
							?>
						</div>
                  <div class="fb-content" >
					<?php the_content(); ?>
			   </div>
                   
                    			        <?php endwhile; // end of the loop. ?>
</div><!-- #main -->

</body>
</html>
