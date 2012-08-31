<?php
/**
 * Template Name Posts: PPC Page
 * @package ProGo
 * @subpackage SmallBusiness
 * @since SmallBusiness 1.0
 */


get_header('ppc');
?>
	<div id="bg">
    <div id="container" class="container_12">
        <div id="pagewrap">
            <div id="main" class="grid_8">
<div class="entry" style="padding-left:25px;">
<?php
if ( function_exists( 'progo_populate_ppc_content' ) ) {
	global $post;
	echo '<pre style="display:none" title="before pop">'. print_r($post,true) .'</pre>';
	progo_populate_ppc_content();
} else {
	 if ( have_posts() ) while ( have_posts() ) : the_post(); ?>
				 		            
                    <div class="content"><?php the_content(); ?></div>
                    <?php endwhile; // end of the loop. 
}
?>

<div class="form_area">
				<?php
				
									
	echo '<pre style="display:none" title="after">'. print_r($post,true) .'</pre>';
					$ppc_form_title = get_post_meta(progo_ppc_get_id(), '_ppc_form_title', true);
						echo '<h2>'. $ppc_form_title .'</h2>';?>
				<div style="width:100%; padding-left:220px;">
				<?php
					$PPCForm = get_post_meta(progo_ppc_get_id(), '_ppc_form_shortcode', true);
					echo apply_filters('the_content', $PPCForm);
				?>
				</div>
</div>
<div class="below_form">
<h1><?php progo_ppc_title(); ?></h1>
<br />

<?php
					$ppc_form_content = get_post_meta(progo_ppc_get_id(), '_ppcformcontent', true);
					echo apply_filters('the_content', $ppc_form_content);

				?>
</div>
</div><!-- .entry -->
</div><!-- #main -->

        </div><!-- #pagewrap -->
    </div><!-- #container -->
	</div><!-- #bg -->

<?php get_footer(); ?>
