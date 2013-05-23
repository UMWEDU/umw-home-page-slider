<?php
/**
 * Define the UMW_Home_Slider_Widget class
 */
class UMW_Home_Slider_Widget extends WP_Widget {
	function __construct() {
		parent::__construct( 'umw_home_slider', __( 'Home Page Slider' ), array( 'description' => __( 'The slideshow used on the UMW home page. This widget should not be used anywhere else on the website.' ) ) );
	}
	
	function form( $instance ) {
		global $umw_home_page_slideshow_obj;
		if ( class_exists( 'UMW_Home_Page_Slideshow' ) && ! isset( $umw_home_page_slideshow_obj ) )
			$umw_home_page_slideshow_obj = new UMW_Home_Page_Slideshow;
		$instance = $umw_home_page_slideshow_obj->get_defaults( $instance );
?>
<p><label for="<?php echo $this->get_field_id( 'feed' ) ?>"><?php _e( 'Feed URL:' ) ?></label> 
	<input class="widefat" type="url" name="<?php echo $this->get_field_name( 'feed' ) ?>" id="<?php echo $this->get_field_id( 'feed' ) ?>" value="<?php echo $instance['feed'] ?>" /></p>
<p><label for="<?php echo $this->get_field_id( 'animation' ) ?>"><?php _e( 'Animation Type:' ) ?></label> 
	<select class="widefat" name="<?php echo $this->get_field_name( 'animation' ) ?>" id="<?php echo $this->get_field_id( 'animation' ) ?>">
    	<option value="slide"<?php selected( $instance['animation'], 'slide' ) ?>><?php _e( 'Slide' ) ?></option>
        <option value="fade"<?php selected( $instance['animation'], 'fade' ) ?>><?php _e( 'Fade' ) ?></option>
    </select></p>
<p><label for="<?php echo $this->get_field_id( 'slideshowSpeed' ) ?>"><?php _e( 'Show each slide for how many seconds?' ) ?></label> 
	<input type="number" name="<?php echo $this->get_field_name( 'slideshowSpeed' ) ?>" id="<?php echo $this->get_field_id( 'slideshowSpeed' ) ?>" value="<?php echo ( intval( $instance['slideshowSpeed'] ) / 1000 ) ?>"/></p>
<p><label for="<?php echo $this->get_field_id( 'animationSpeed' ) ?>"><?php _e( 'How many seconds should the slide/fade animation last?' ) ?></label> 
	<input type="number" name="<?php echo $this->get_field_name( 'animationSpeed' ) ?>" id="<?php echo $this->get_field_id( 'animationSpeed' ) ?>" value="<?php echo ( intval( $instance['animationSpeed'] ) / 1000 ) ?>"/></p>
<p><label for="<?php echo $this->get_field_id( 'direction' ) ?>"><?php _e( 'Slide direction:' ) ?></label> 
	<select class="widefat" name="<?php echo $this->get_field_name( 'direction' ) ?>" id="<?php echo $this->get_field_id( 'direction' ) ?>">
    	<option value="horizontal"<?php selected( $instance['direction'], 'horizontal' ) ?>><?php _e( 'Horizontal' ) ?></option>
        <option value="vertical"<?php selected( $instance['direction'], 'vertical' ) ?>><?php _e( 'Vertical' ) ?></option>
    </select></p>
<p><input type="checkbox" name="<?php echo $this->get_field_name( 'slideshow' ) ?>" id="<?php echo $this->get_field_id( 'slideshow' ) ?>" value="1"<?php checked( $instance['slideshow'] ) ?>/> 
	<label for="<?php echo $this->get_field_id( 'slideshow' ) ?>"><?php _e( 'Start slideshow automatically?' ) ?></label></p>
<p><input type="checkbox" name="<?php echo $this->get_field_name( 'randomize' ) ?>" id="<?php echo $this->get_field_id( 'randomize' ) ?>" value="1"<?php checked( $instance['randomize'] ) ?>/> 
	<label for="<?php echo $this->get_field_id( 'randomize' ) ?>"><?php _e( 'Randomize slide order?' ) ?></label></p>
<p><input type="checkbox" name="<?php echo $this->get_field_name( 'pausePlay' ) ?>" id="<?php echo $this->get_field_id( 'pausePlay' ) ?>" value="1"<?php checked( $instance['pausePlay'] ) ?>/> 
	<label for="<?php echo $this->get_field_id( 'pausePlay' ) ?>"><?php _e( 'Show pause/play buttons?' ) ?></label></p>
<p><input type="checkbox" name="<?php echo $this->get_field_name( 'pauseOnAction' ) ?>" id="<?php echo $this->get_field_id( 'pauseOnAction' ) ?>" value="1"<?php checked( $instance['pauseOnAction'] ) ?>/> 
	<label for="<?php echo $this->get_field_id( 'pauseOnAction' ) ?>"><?php _e( 'Pause the slideshow automatically when someone interacts with it?' ) ?></label></p>
<p><input type="checkbox" name="<?php echo $this->get_field_name( 'animationLoop' ) ?>" id="<?php echo $this->get_field_id( 'animationLoop' ) ?>" value="1"<?php checked( $instance['animationLoop'] ) ?>/> 
	<label for="<?php echo $this->get_field_id( 'animationLoop' ) ?>"><?php _e( 'Loop the slideshow to start over from beginning after last slide?' ) ?></label></p>
<p><input type="checkbox" name="<?php echo $this->get_field_name( 'video' ) ?>" id="<?php echo $this->get_field_id( 'video' ) ?>" value="1"<?php checked( $instance['video'] ) ?>/> 
	<label for="<?php echo $this->get_field_id( 'video' ) ?>"><?php _e( 'Will there be video included in the slideshow?' ) ?></label></p>
<p><input type="checkbox" name="<?php echo $this->get_field_name( 'controlNav' ) ?>" id="<?php echo $this->get_field_id( 'controlNav' ) ?>" value="1"<?php checked( $instance['controlNav'] ) ?>/> 
	<label for="<?php echo $this->get_field_id( 'controlNav' ) ?>"><?php _e( 'Show a navigation indicator for each slide in the slideshow?' ) ?></label></p>
<p><input type="checkbox" name="<?php echo $this->get_field_name( 'directionNav' ) ?>" id="<?php echo $this->get_field_id( 'directionNav' ) ?>" value="1"<?php checked( $instance['directionNav'] ) ?>/> 
	<label for="<?php echo $this->get_field_id( 'directionNav' ) ?>"><?php _e( 'Show previous/next buttons?' ) ?></label></p>
<p><input type="checkbox" name="<?php echo $this->get_field_name( 'keyboard' ) ?>" id="<?php echo $this->get_field_id( 'keyboard' ) ?>" value="1"<?php checked( $instance['keyboard'] ) ?>/> 
	<label for="<?php echo $this->get_field_id( 'keyboard' ) ?>"><?php _e( 'Allow keyboard navigation of slideshow?' ) ?></label></p>
<p><input type="checkbox" name="<?php echo $this->get_field_name( 'mousewheel' ) ?>" id="<?php echo $this->get_field_id( 'mousewheel' ) ?>" value="1"<?php checked( $instance['mousewheel'] ) ?>/> 
	<label for="<?php echo $this->get_field_id( 'mousewheel' ) ?>"><?php _e( 'Allow scroll-wheel navigation of slideshow?' ) ?></label></p>
<?php
		return;
	}
	
	function update( $new_instance, $old_instance ) {
		global $umw_home_page_slideshow_obj;
		if ( class_exists( 'UMW_Home_Page_Slideshow' ) && ! isset( $umw_home_page_slideshow_obj ) )
			$umw_home_page_slideshow_obj = new UMW_Home_Page_Slideshow;
		$defaults = $umw_home_page_slideshow_obj->get_defaults( $instance );
		$bool = $instance = array();
		foreach ( $defaults as $k => $v ) {
			if ( array_key_exists( $k, $new_instance ) ) {
				$instance[$k] = $new_instance[$k];
				if ( false === $v || true === $v )
					$instance[$k] = true;
				elseif ( is_numeric( $v ) )
					$instance[$k] = $new_instance[$k] * 1000;
			} else {
				if ( false === $v || true === $v )
					$instance[$k] = false;
				else
					$instance[$k] = null;
			}
		}
		return $instance;
	}
	
	function widget( $args, $instance ) {
		global $umw_home_page_slideshow_obj;
		if ( class_exists( 'UMW_Home_Page_Slideshow' ) && ! isset( $umw_home_page_slideshow_obj ) )
			$umw_home_page_slideshow_obj = new UMW_Home_Page_Slideshow;
		
		$instance['title'] = null;
		$title = empty( $instance['title'] ) ? null : $args['before_title'] . esc_attr( $instance['title'] ) . $args['after_title'];
		echo $instance['before_widget'];
		echo $title;
		$umw_home_page_slideshow_obj->slider( $instance );
		echo $instance['after_widget'];
	}
}
