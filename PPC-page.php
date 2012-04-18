<?php
/**
 * Template Name Posts: PPC Page
 * @package ProGo
 * @subpackage SmallBusiness
 * @since SmallBusiness 1.0
 */


get_header('ppc');
?>
<div id="container" class="container_12">
<div id="pagetop">
<h1 class="page-title"><?php ppc_title(); ?></h1>
<?php do_action('progo_pagetop'); ?>
</div>
<div id="main" role="main" class="grid_8">
<div id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
<div class="entry">
<?php if (function_exists('populate_content')){
						

							populate_content();
	 
	 
							}
							else {  the_content(); }
							?>
</div><!-- .entry -->
</div><!-- #post-## -->
</div><!-- #main -->

<!-- #container -->
<?php get_sidebar('ppc'); ?>
</div>

<?php get_footer(); ?>