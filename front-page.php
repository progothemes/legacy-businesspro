<?php
/**
 * @package ProGo
 * @subpackage Business Pro
 * @since Business Pro 1.0
 */
get_header();
global $wp_query, $post;
$options = get_option( 'progo_options' );
if ( isset( $options['layout'] ) == false ) {
	$options = array();
	$options['layout'] = 1;
}
$pagetopW = 12;
?>
	<div id="bg">
    <div id="homeslides" class="container_12">
<div id="pagetop" class="slides grid_<?php echo $pagetopW .' Layout'. $options['layout']; ?>">
<?php
$original_query = $wp_query;
$slides = get_posts('post_type=progo_homeslide&post_status=publish&posts_per_page=-1&orderby=menu_order&order=ASC');
$count = count($slides);
$oneon = false;
$ogpost = $post;
foreach ( $slides as $s ) {
	$post = $s;
	$on = '';
	if ( $oneon == false ) {
		$oneon = true;
		$on = ' on';
	}
	
	$bg = ' Light';
	$imgsize = 'homeslide';
	$thmsrc = get_bloginfo( 'template_url' ) .'/images/slides/businesspeoples';
	if( $options['layout'] == 2 ) {
		$imgsize .= '3';
		$thmsrc .= '-480';
	}
	$thmsrc .= '.jpg';
	if ( get_post_thumbnail_id( $s->ID ) ) {
		$thm = get_the_post_thumbnail($s->ID, $imgsize);
		$thmsrc = strpos($thm, 'src="') + 5;
		$thmsrc = substr($thm, $thmsrc, strpos($thm,'"',$thmsrc+1)-$thmsrc);
	}
		
	if ( $options['layout'] == 2 ) {
		$bg .= ' custombg "><img src="'. $thmsrc;
	} else {
		$bg .= ' custombg " style="background-image: url('. $thmsrc .')';
	}
	
	echo '<div class="textslide slide'. $on . $bg .'"><div class="inside">';
	echo '<div class="page-title">'. str_replace('|', '<br />', wp_kses($s->post_title,array())) .'</div>';
	if ( $options['layout'] != 3 ) {
		echo '<div class="content productcol">'. apply_filters('the_content',$s->post_content) .'</div>';
	}
	echo '</div>'. ($pagetopW==12 ? '<div class="shadow"></div>' : '') .'</div>';
}
$post = $ogpost;
if ( $oneon == true && $count > 1 ) { ?>
<div class="ar"><a href="#p" title="Previous Slide"></a><a href="#n" class="n" title="Next Slide"></a></div>
<script type="text/javascript">
progo_timing = <?php $hsecs = absint($options['homeseconds']); echo $hsecs > 0 ? $hsecs * 1000 : "0"; ?>;
</script>
<?php
}
do_action('progo_pagetop');
if ($pagetopW==8) echo '<div class="shadow"></div>';
?>
</div>
<?php
if ( $options['layout'] > 2 ) {
	get_sidebar('pbpform');
}
?>
</div><!-- /homeslides -->
</div><!-- /bg -->
    <div id="container" class="container_12">
        <div id="pagewrap">
<div id="main" class="grid_8">
<?php

if ( current_user_can('edit_theme_options') ) {
	$options = get_option( 'progo_options' );
	if ( (int) $options['showtips'] == 1 ) {
		echo '<a style="position: relative" class="ptip" href="'. admin_url('themes.php?page=theme_options#progo_homepage') .'"><span>Choose to have your Home page display Latest Blog Posts or Static Content via Appearance > Theme Options</span></a>';
	}
}

rewind_posts();
$onfront = get_option( 'show_on_front' );
if ( isset( $options['frontpage'] ) ) {
	$onfront = $options['frontpage'];
}
switch ( $onfront ) {
	case 'posts':
		get_template_part( 'loop', 'index' );
		break;
	case 'page':
		if ( have_posts() ) while ( have_posts() ) : the_post(); ?>
		<div id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
		<div class="entry">
		<?php the_content(); ?>
		</div><!-- .entry -->
		</div><!-- #post-## -->
		<?php
		endwhile;
		break;
}
?>
</div><!-- #main -->
<div class="grid_4 secondary">
<?php
if ( $options['layout'] < 3 ) {
	get_sidebar('pbpform');
}
if($options['frontpage'] == 'posts') {
	get_sidebar('blog');
} else {
	get_sidebar();
} ?>
</div>
</div><!-- #pagewrap -->
</div><!-- #container -->
<?php get_footer(); ?>