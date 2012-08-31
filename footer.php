<?php
/**
 * The template for displaying the footer.
 *
 * Contains the closing of the id=main div and all content
 * after.  Calls sidebar-footer.php for bottom widgets.
 *
 * @package ProGo
 * @subpackage Business Pro
 * @since Business Pro 1.0
 */
?>
    <div id="frap">
        <div id="ftr" class="container_12">
            <div id="fbar">
            <?php
            if(!dynamic_sidebar('fbar')) {
            	wp_nav_menu( array( 'container_class' => 'fblock widget_nav_menu', 'theme_location' => 'fbarlnx' ) );
            }
			?>
            </div>
            <div class="grid_8<?php
            $fmenu = wp_nav_menu( array( 'container' => false, 'theme_location' => 'ftrlnx', 'echo' => 0, 'fallback_cb' => 'progo_nomenu_cb' ) );
            if( strpos( $fmenu, '</li>' ) > 0 ) {
				$fmenu = str_replace('</li>','&nbsp;&nbsp;|&nbsp;&nbsp;</li>',substr($fmenu,0,strrpos($fmenu,'</li>'))) . "</li>\n</ul>";
				echo '">'. $fmenu .'<br />';
            } else {
            	echo ' nom">';
            }
            
            $options = get_option('progo_options');
            
            if( ($options['businessaddy'] != "") || ($options['businessCSZ'] != "") || ($options['businessphone'] != "") ) {
            	echo $options['businessaddy']. " ". $options['businessCSZ'];
            if( ($options['businessaddy'] != "") || ($options['businessCSZ'] != "") ) echo " - ";
            	echo $options['businessphone'] .'<br />';
            }
            
            if ( isset( $options['copyright'] ) ) {
            	echo wp_kses($options['copyright'],array());
            } else {
            	echo '&copy; Copyright '. date('Y') .', All Rights Reserved';
            }
            ?>
            </div>
            <div class="grid_4 right">
            <?php do_action( 'progo_poweredby' ); ?>
            </div>
        </div><!-- #ftr -->
    </div><!-- #frap -->
</div><!-- #fx -->
<?php
	/* Always have wp_footer() just before the closing </body>
	 * tag of your theme, or you will break many plugins, which
	 * generally use this hook to reference JavaScript files.
	 */

	wp_footer();
?>
</body>
</html>
