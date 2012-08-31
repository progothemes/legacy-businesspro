<?php
/**
 * The Sidebar containing the primary and secondary widget areas.
 *
 * @package ProGo
 * @subpackage SmallBusiness
 * @since SmallBusiness 1.0
 */
?>
<div class="grid_4">
<div id="secondary">
<?php
/* When we call the dynamic_sidebar() function, it'll spit out
 * the widgets for that widget area. If it instead returns false,
 * then the sidebar simply doesn't exist, so we'll hard-code in
 * some default sidebar stuff just in case.
 */
if ( ! dynamic_sidebar( 'ppc-template' ) ) :
// do some default something?
?>
 <?php endif; // end primary widget area ?>
</div>
</div>