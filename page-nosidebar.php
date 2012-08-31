<?php
/**
 * Template Name: No Sidebar
 *
 * @package ProGo
 * @subpackage Business Pro
 * @since Business Pro 1.0
 */

get_header(); ?>
	<div id="bg">
    <div id="container" class="container_12">
		<?php if ( have_posts() ) while ( have_posts() ) : the_post(); ?>
        <div id="pagetop">
            <h1 class="page-title"><?php the_title(); ?></h1>
            <?php do_action('progo_pagetop'); ?>
        </div>
        <div id="pagewrap">
            <div id="main" class="grid_12">
                <div id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
                    <div class="entry">
                    <?php
                    if ( function_exists('yoast_breadcrumb') ) {
                        yoast_breadcrumb('<p id="breadcrumbs">','</p>');
                    }
                    the_content();
                    ?>
                    </div><!-- .entry -->
                </div><!-- #post-## -->
            </div><!-- #main -->
            <?php endwhile; ?>
        </div><!-- #pagewrap -->
    </div><!-- #container -->
	</div><!-- #bg -->
<?php get_footer(); ?>