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

<body class="container_1 fb" style="background:#fff;">
<div id="fb-root"></div>
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
		<div class="fb-left" >
			<span class="fb-headline" >
				<?php //the_title(); ?>
				<?php 
					$FB_pre_headline = get_post_meta($post->ID, '_FB_PRE_HEADLINE', true);
					print_r($FB_pre_headline);
				?>
			</span>
			<span class="fb-old-headline">
				<h2>
					<?php
						$FB_Tab_meta_headline = get_post_meta($post->ID, '_FB_Tab_meta_headline', true);
						print_r($FB_Tab_meta_headline);
					?>
				</h2>
			</span>	
			<div class="fb-bullets" >
				<?php  $fb_bullets = get_post_meta($post->ID, '_FB_BULLET', true);
				if ( $fb_bullets == '' ) {
					$fb_bullets = array(
						1 => '',
						2 => '',
						3 => '',
						4 => '',
						'image' => '',
					);
				}
				?>
				<ul class="bf-bullets" <?php if($fb_bullets['image'] != '') : ?> style='list-style-image:url(<?php echo $fb_bullets['image']; ?>);' <?php endif; ?>>
					<?php if($fb_bullets[1] != '') : ?> <li><?php echo $fb_bullets[1]; ?></li><?php endif; ?>
					<?php if($fb_bullets[2] != '') : ?> <li><?php echo $fb_bullets[2]; ?></li><?php endif; ?>
					<?php if($fb_bullets[3] != '') : ?> <li><?php echo $fb_bullets[3]; ?></li><?php endif; ?>
					<?php if($fb_bullets[4] != '') : ?> <li><?php echo $fb_bullets[4]; ?></li><?php endif; ?>
				</ul>
			</div>
		</div>
		<div class="fb-right" >
		   <div class="fb-arrow" >
				<img src="<?php bloginfo( 'stylesheet_directory' ); ?>/images/arrow.png" />
		   </div>
		   <div class="fb-form cstm_buttons fb-form1" >
			   <?php
					$FBTabForm = get_post_meta($post->ID, '_FB_Tab_meta_form', true);
					echo apply_filters('the_content', $FBTabForm);
				?>				            
			</div>
		</div>
		<div class="clear" ></div>
		<div class="fb-content" >
			<?php the_content(); ?>
	   </div>
			<?php endwhile; // end of the loop. ?>
	</div><!-- #main -->

</body>
</html>
