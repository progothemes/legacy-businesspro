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
<div id="main" role="main" class="grid_12">
<div class="grid_7" style="position:relative; float:left;">
<h1><?php progo_ppc_title(); ?></h1><br />
<?php
if ( function_exists( 'progo_populate_ppc_content' ) ) {
	progo_populate_ppc_content();
	
} else {
	 if ( have_posts() ) while ( have_posts() ) : the_post(); ?>
				 		            
                    <div class="content"><?php the_content(); ?></div>
                    <?php endwhile; // end of the loop. 
}
?>
</div>
<div style="position:relative; float:right; height:auto;" class="pbpform">
		<div class="ppc_2_form_title">
		<table class="tar" width="100%">
		<tr>
			<td> <?php $ppc_form_title = get_post_meta(progo_ppc_get_id(), '_ppc_form_title', true);
						echo '<h4>'. $ppc_form_title .'</h4>';?>  </td>
		</tr>
		</table>
						</div>
		<div ><?php
					$PPCForm = get_post_meta(progo_ppc_get_id(), '_ppc_form_shortcode', true);
					echo apply_filters('the_content', $PPCForm);
				?></div>
				<br class="clear:both;" />
</div>
<!-- .entry -->
<div style="clear:both;"><br /><br /></div>
</div><!-- #main -->

        </div><!-- #pagewrap -->
    </div><!-- #container -->
	</div><!-- #bg -->
<?php get_footer(); ?>
