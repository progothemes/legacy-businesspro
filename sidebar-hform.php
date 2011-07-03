<?php
/**
 * Homepage Form stored in another Sidebar.
 *
 * @package ProGo
 * @subpackage Business Pro
 * @since Business Pro 1.0
 */

$options = get_option( 'progo_options' );
?>
<div class="hform">
<table class="tar" width="100%"><tr><td><?php echo nl2br(wp_kses($options['headline'], array())); ?></td></tr></table>
<?php echo apply_filters('the_content', $options['form']); ?>
</div>