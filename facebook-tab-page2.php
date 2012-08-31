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

<link rel="stylesheet" type="text/css" media="all" href="<?php bloginfo( 'stylesheet_directory' ); ?>/css/facebook-tabs.css" />
<link rel="stylesheet" type="text/css" media="all" href="<?php bloginfo( 'stylesheet_url' ); ?>" />
<script type="text/javascript" src="<?php bloginfo( 'stylesheet_directory' ); ?>/mediaPlayer-5.9/jwplayer.js"></script>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.6.1/jquery.min.js"></script>
</head>

<body class="container_12 page_2 fb" style="background:#fff;">
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
						<?php 
							$FB_pre_headline = get_post_meta($post->ID, '_FB_PRE_HEADLINE', true);
							print_r($FB_pre_headline);
						?>
					</span>   
						<span class="fb-old-headline"><h2>
							<?php
								$FB_Tab_meta_headline = get_post_meta($post->ID, '_FB_Tab_meta_headline', true);
								print_r($FB_Tab_meta_headline);
							?></h2>
						</span>
						<div id="container">
							<?php
								$FBFeatureImg = get_post_meta($post->ID, '_FB_FEATURE_IMG', true);
								echo "<img src='".$FBFeatureImg."' width=450px; />"
							?>
						</div>
						<!--<script type="text/javascript">
    						jwplayer("container").setup({
        					flashplayer: "<?php bloginfo( 'stylesheet_directory' ); ?>/mediaPlayer-5.9/player.swf",
        					file: "<?php echo get_post_meta($post->ID, '_FB_Tab_meta_video', true);?>",
        					skin: "<?php bloginfo( 'stylesheet_directory' ); ?>/mediaPlayer-5.9/facebook.zip",
        					height: 270,
        					width: 480,
        					image:"<?php echo get_post_meta($post->ID, '_FB_Tab_meta_thumb', true);?>",
        					plugins: {
        								"gapro-2": {},
        								'tweetit-1': {},
        								'viral-2':{'onpause': 'false'}
        					    	 }
    						});
						</script>-->
					</div>
					<div class="fb-right" >
						<div class="fb-arrow" >
							<img src="<?php bloginfo( 'stylesheet_directory' ); ?>/images/arrow.png" />
					   </div>
						 <div class="fb-form cstm_buttons fb-form2" >
							   <?php
									$FBTabForm = get_post_meta($post->ID, '_FB_Tab_meta_form', true);
									echo apply_filters('the_content', $FBTabForm);
									//$FBFeatureImg = get_post_meta($post->ID, '_FB_FEATURE_IMG', true);
									//echo "<img src='".$FBFeatureImg."' width=640 />"
								?>
								<!--<button class="fb-form_button2">Click Here To Get Instant Access</button>-->
							</div>
					</div>	
				<div class="fb-content" >
					<?php the_content(); ?>
			   </div>
                    			        <?php endwhile; // end of the loop. ?>
                    			       
	</div><!-- #main -->


</body>
</html>
