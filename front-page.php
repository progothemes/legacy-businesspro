<?php
/**
 * @package ProGo
 * @subpackage Business Pro
 * @since Business Pro 1.0
 */

get_header();
global $wp_query, $post;
$options = get_option( 'progo_options' );

switch( $options['layout'] ) {
	case 1:
	case 2:
		$pagetopW = 8;
		break;
	case 3:
	case 4:
		$pagetopW = 12;
		break;
}
?>
<div id="container" class="container_12">
<div id="pagetop" class="slides grid_<?php echo $pagetopW .' Layout'. $options['layout']; ?>">
<?php
$original_query = $wp_query;
$slides = get_posts('post_type=progo_homeslide&post_status=publish&posts_per_page=-1&orderby=menu_order&order=ASC');
$count = count($slides);
$oneon = false;
foreach ( $slides as $s ) {
	$on = '';
	if ( $oneon == false ) {
		$oneon = true;
		$on = ' on';
	}
	
	$slidecustom = get_post_meta($s->ID,'_progo_slidecontent');
	$slidecontent = (array) $slidecustom[0];
	$bg = ' '. $slidecontent['textcolor'];
	if ( get_post_thumbnail_id( $s->ID ) ) {
		switch( $options['layout'] ) {
			case 3:
				$imgsize = 'homeslide3';
				break;
			case 4:
				$imgsize = 'homeslide4';
				break;
			default:
				$imgsize = 'homeslide';
				break;
		}
		
		$thm = get_the_post_thumbnail($s->ID, $imgsize);
		$thmsrc = strpos($thm, 'src="') + 5;
		$thmsrc = substr($thm, $thmsrc, strpos($thm,'"',$thmsrc+1)-$thmsrc);
		$bg .= ' custombg " style="background-image: url('. $thmsrc .')';
	}
	
	/*
	switch( $options['layout'] ) {
		default:
	*/
	echo '<div class="textslide slide'. $on . $bg .'"><div class="inside"><div class="page-title">'. wp_kses($s->post_title,array()) .'</div>';
	echo '<div class="content productcol">'. apply_filters('the_content',$slidecontent['text']) .'</div></div>'. ($pagetopW==12 ? '<div class="shadow"></div>' : '') .'</div>';
	/*break;
	}
	*/
}
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
if ( $options['layout'] < 3 ) {
	get_sidebar('hform');
}
?>
<div id="main" class="grid_8">
<?php
rewind_posts();
switch ( $options['frontpage'] ) {
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
if ( $options['layout'] > 2 ) {
	get_sidebar('hform');
}
if($options['frontpage'] == 'posts') {
	get_sidebar('blog');
} else {
	get_sidebar();
} ?>
</div>
</div><!-- #container -->
<?php get_footer(); ?>