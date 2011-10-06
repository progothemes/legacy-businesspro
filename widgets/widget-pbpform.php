<?php
/**
 * ProGo Themes' Support Widget Class
 *
 * This widget is for positioning/removing the "Customer Support" block
 *
 * @since 1.0.0
 *
 * @package ProGo
 * @subpackage Core
 */

class ProGo_Widget_PBPForm extends WP_Widget {

	var $prefix;
	var $textdomain;

	/**
	 * Set up the widget's unique name, ID, class, description, and other options.
	 * @since 1.0.0
	 */
	function ProGo_Widget_PBPForm() {
		$this->prefix = 'progo';
		$this->textdomain = 'progo';

		$widget_ops = array( 'classname' => 'pbpform', 'description' => __( 'Widgetized version of the Homepage form', $this->textdomain ) );
		$this->WP_Widget( "{$this->prefix}-support", __( 'ProGo : Pro Form', $this->textdomain ), $widget_ops );
	}

	/**
	 * Outputs the widget based on the arguments input through the widget controls.
	 * @since 1.0.0
	 */
	function widget( $args, $instance ) {
		get_sidebar('pbpform');
	}

	/**
	 * Updates the widget control options for the particular instance of the widget.
	 * @since 1.0.0
	 */
	function update( $new_instance, $old_instance ) {
		$instance = $old_instance;
		/*
		$new_instance = wp_parse_args( (array) $new_instance, array( 'title' => '', 'count' => 0, 'dropdown' => '') );
		$instance['title'] = strip_tags($new_instance['title']);
		*/
		return $instance;
	}

	/**
	 * Displays the widget control options in the Widgets admin screen.
	 * @since 1.0.0
	 */
	function form( $instance ) {
		/*
		$instance = wp_parse_args( (array) $instance, array( 'title' => '', 'count' => 0, 'dropdown' => '') );
		$title = strip_tags($instance['title']);
?>
		<p><label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title:'); ?></label> <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo esc_attr($title); ?>" /></p>
<?php
		*/
	}
}

?>