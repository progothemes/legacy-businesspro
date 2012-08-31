<?php
/**
 * The main template file.
 *
 * This is the most generic template file in a WordPress theme
 * and one of the two required files for a theme (the other being style.css).
 * It is used to display a page when nothing more specific matches a query. 
 * E.g., it puts together the home page when no home.php file exists.
 * Learn more: http://codex.wordpress.org/Template_Hierarchy
 *
 * @package ProGo
 * @subpackage Business Pro
 * @since Business Pro 1.0
 */
get_header(); ?>
	<div id="bg">
    <div id="container" class="container_12">
        <div id="pagetop">
            <h1 class="page-title"><?php
            if ( is_author() ) {
                global $wp_query;
                printf( __( 'Author Archives: %s', 'businesspro' ), "<span class='vcard'><a class='url fn n' href='" . get_author_posts_url( get_the_author_meta( 'ID', $wp_query->query_vars['author'] ) ) . "' title='" . esc_attr( get_the_author_meta( 'display_name' ) ) . "' rel='me'>" . get_the_author_meta( 'display_name', $wp_query->query_vars['author'] ) . "</a></span>" );
            } elseif ( is_category() ) {
                single_cat_title( '' );
            } elseif ( is_tag() ) {
                single_tag_title( '' );
            } elseif ( is_day() ) {
                printf( __( 'Daily Archives: <span>%s</span>', 'businesspro' ), get_the_date() );
            } elseif ( is_month() ) {
                printf( __( 'Monthly Archives: <span>%s</span>', 'businesspro' ), get_the_date( _x( 'F Y', 'monthly archives date format', 'businesspro' ) ) );
            } elseif ( is_year() ) {
                printf( __( 'Yearly Archives: <span>%s</span>', 'businesspro' ), get_the_date( _x( 'Y', 'yearly archives date format', 'businesspro' ) ) );
            } else {
                echo get_the_title( get_option('progo_blog_id') );
            } ?></h1>
            <?php do_action('progo_pagetop'); ?>
        </div>
        <div id="pagewrap">
            <div id="main" class="grid_8">
				<?php
                /* Run the loop to output the posts.
                * If you want to overload this in a child theme then include a file
                * called loop-index.php and that will be used instead.
                */
                get_template_part( 'loop', 'index' );
                ?>
            </div><!-- #main -->
            <div class="grid_4 secondary">
            	<?php get_sidebar('blog'); ?>
            </div>
        </div><!-- #pagewrap -->
	</div><!-- #container -->
	</div><!-- #bg -->
<?php get_footer(); ?>
