<?php
/**
 * ProGo Themes' OfficeInfo Widget Class
 *
 * This widget is for positioning/removing the "Office Information" widget block
 *
 * @since 1.2.6
 *
 * @package ProGo
 * @subpackage Core
 */

class ProGo_Widget_OfficeInfo extends WP_Widget {

	var $prefix;
	var $textdomain;
	
	function ProGo_Widget_OfficeInfo() {
		$this->prefix = 'progo';
		$this->textdomain = 'progo';
		
		/* Widget settings. */
		$widget_ops = array( 'classname' => 'officeInfo', 'description' => 'A widget to easily display your offices information and directions.' );

		/* Widget control settings. */
		//$control_ops = array( 'width' => 300, 'height' => 350, 'id_base' => 'office-info-widget' );

		$this->WP_Widget( "{$this->prefix}-office-info", __( 'ProGo : Office Information', $this->textdomain ), $widget_ops );
	}
	
	function widget( $args ) {
		extract( $args );
		$options = get_option( 'progo_options' );
		
		$title = "Office Info";
		$busnessaddress = $options['businessaddy'];
		$businessCSZ = $options['businessCSZ'];
		$businessphone = $options['businessphone'];
		$businessemail = $options['businessemail'];
		$businesshours = $options['businesshours'];
		

		/* Before widget (defined by themes). */
		echo $before_widget;

		if ( $title )
			echo $before_title . $title . $after_title;

		/* Display Business Address from widget settings. */
		
		
		if($busnessaddress != "" && $businessCSZ != ""){
			$mapbad = urlencode( esc_attr($busnessaddress) .' '. esc_attr($businessCSZ) );
			echo '<iframe width="100%" height="220px" frameborder="0" scrolling="no" marginheight="0" marginwidth="0" src="http://maps.google.com/maps?q='. $mapbad . '&amp;hl=en&amp;iwloc=&amp;output=embed"></iframe><br />';
		}
		
		if ( $busnessaddress != ""){
			echo '<br /><span>' . $busnessaddress . '</span><br />';
		}
		if ( $businessCSZ != ""){
			echo '<span>' . $businessCSZ . '</span><br />';
		}
		
		
		
		if ( $businessphone != ""){
			echo '<br /><span>CALL: ' . $businessphone . '</span><br />';
		}
		
		if ( $businessemail != ""){
			
			echo '<span>EMAIL: ' . $businessemail . '</span><br />';
		}
		
		
		
		if ( $businesshours != ""){
			echo '<br /><h4>Hours of Operation:</h4>';
			echo '<span>' . $businesshours . '</span><br />';
		}
		
		/* After widget (defined by themes). */
		echo $after_widget;
	}
	
	function form( $instance ) {
		$options = get_option( 'progo_options' );
		?>
		<div>Set Office Info options <a href="themes.php?page=theme_options#progo_office">here</a></div>
		<?php
	}
}

?>