<?php
/**
 * The template for displaying Search Results pages.
 *
 * @package ProGo
 * @subpackage Business Pro
 * @since Business Pro 1.0
 */

get_header(); ?>
    <div id="container" class="container_12">
<?php if ( have_posts() ) : ?>
		<div id="pagetop">
			<h1 class="page-title"><?php printf( __( 'Search Results for: %s', 'progo' ), '<span>' . get_search_query() . '</span>' ); ?></h1>
        </div>
		<div id="main" class="grid_8">
				<?php
				/* Run the loop for the search to output the results.
				 * If you want to overload this in a child theme then include a file
				 * called loop-search.php and that will be used instead.
				 */
				 get_template_part( 'loop', 'search' );
				?>
<?php else : ?>
		<div id="pagetop">
			<h1 class="page-title"><?php __( 'Nothing Found', 'progo' ); ?></h1>
        </div>
		<div id="main" class="grid_8">
				<div id="post-0" class="post no-results not-found">
					<div class="entry">
						<p><?php _e( 'Sorry, but nothing matched your search criteria. Please try again with some different keywords.', 'progo' ); ?></p>
						<?php get_search_form(); ?>
					</div><!-- .entry -->
				</div><!-- #post-0 -->
<?php endif; ?>
</div><!-- #main -->
<div class="grid_4 secondary">
<?php get_sidebar(); ?>
</div>
</div>
<?php get_footer(); ?>
