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
			
		
		if ( $busnessaddress != ""){
			echo '<h4>Address:</h4>';
			echo '<span>' . $busnessaddress . '</span><br />';
		}
		if ( $businessCSZ != ""){
			
			echo '<span>' . $businessCSZ . '</span><br />';
		}
		
		
		
		if ( $businessphone != ""){
		
			echo '<br /><h4>Contact:</h4>';
			echo '<span>' . $businessphone . '</span><br />';
		}
		
		if ( $businessemail != ""){
			
			echo '<span>' . $businessemail . '</span><br />';
		}
		
		
		
		if ( $businesshours != ""){
			echo '<br /><h4>Hours of Operation:</h4>';
			echo '<span>' . $businesshours . '</span><br />';
		}
		
		if($busnessaddress != "" && $businessCSZ != ""){
			echo '<br /><h4>Directions:</h4>';
			echo '<div style="border:1px solid #ccc; width:260px; height:260px;"><iframe width="260px" height="260px" frameborder="0" scrolling="no" marginheight="0" marginwidth="0" src="http://maps.google.com/maps?q=' .$busnessaddress . '+' . $businessCSZ . '&hl=en&output=embed"></iframe></div>';
		}
		
		/* After widget (defined by themes). */
		echo $after_widget;
	}
	
	function form( $instance ) {
		$options = get_option( 'progo_options' );
		?>
		<div>Set Office Info options <a href="themes.php?page=progo_admin#progo_office">here</a></div>
		<?php
	}
}

?>