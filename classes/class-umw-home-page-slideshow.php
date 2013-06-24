<?php
/**
 * Define the UMW_Home_Page_Slideshow class
 */
class UMW_Home_Page_Slideshow {
	var $source = 'http://feeds.feedburner.com/umw-greatminds-home/';
	var $feed = null;
	var $slides = array();
	var $show = null;
	var $atts = array();
	var $script_version = '0.1.26';
	
	/**
	 * Construct the UMW Home Page Slideshow object
	 */
	function __construct() {
		$this->enqueue_scripts();
	}
	
	/**
	 * Retrieve an array of the default values for the slider
	 */
	private function _defaults() {
		return apply_filters( 'umw-slider-defaults', array(
			'feed' => esc_url( $this->source ), 
			'animation' => 'slide', 
			'slideshowSpeed' => 7000, 
			'animationSpeed' => 1500, 
			'direction' => 'horizontal', 
			'slideshow' => true, 
			'randomize' => true, 
			'pausePlay' => true, 
			'pauseOnAction' => false, 
			'animationLoop' => true, 
			'video' => false, 
			'controlNav' => true, 
			'directionNav' => true, 
			'keyboard' => true, 
			'mousewheel' => false, 
		) );
	}
	
	/**
	 * Return the current settings
	 */
	function get_defaults( $atts=array() ) {
		return shortcode_atts( $this->_defaults(), $atts );
	}
	
	/**
	 * Register the scripts and styles used in this plugin
	 */
	function enqueue_scripts() {
		if ( ! wp_script_is( 'flexslider', 'registered' ) )
			wp_register_script( 'flexslider', plugins_url( 'scripts/jquery.flexslider/jquery.flexslider-min.js', dirname( __FILE__ ) ), array( 'jquery' ), '2.1', true );
		if ( ! wp_style_is( 'flexStyles', 'registered' ) )
			wp_register_style( 'flexStyles', plugins_url( 'scripts/jquery.flexslider/flexslider.css', dirname( __FILE__ ) ), array(), '2.1', 'all' );
		
		wp_register_style( 'umw-slider', plugins_url( 'styles/umw-home-page-slider.css', dirname( __FILE__ ) ), array( 'flexStyles' ), $this->script_version, 'all' );
		wp_register_script( 'umw-slider', plugins_url( 'scripts/umw-home-page-slider.js', dirname( __FILE__ ) ), array( 'flexslider' ), $this->script_version, true );
	}
	
	/**
	 * Check to make sure we can retrieve the requested feed
	 */
	function test_feed() {
		if ( ! class_exists( 'WP_HTTP' ) )
			include_once( ABSPATH . WPINC. '/class-http.php' );
		
		$request = new WP_HTTP;
		$result = $request->request( esc_url( $this->source ) );
		unset( $request );
		
		if ( is_wp_error( $result ) ) {
			$this->feed = new WP_Error( 'feed-not-found', $result->get_error_message() );
			return false;
		}
		
		if ( 200 != $result['response']['code'] && 304 != $result['response']['code'] ) {
			$this->feed = new WP_Error( 'feed-not-found', __( 'The requested feed returned a status code other than 200 or 304' ) );
			error_log( '[UMW Home Page]: The RSS feed could not be found. The response was ' . $result['response']['code'] );
			return false;
		}
		
		return true;
	}
	
	/**
	 * Retrieve the appropriate items from the requested feed
	 */
	function fetch_feed() {
		if ( ! $this->test_feed() )
			return new WP_Error( 'feed-not-found', __( 'The requested feed could not be retrieved' ) );
		
		if ( ! class_exists( 'SimplePie' ) )
			require_once( ABSPATH . WPINC . '/class-feed.php' );
		
		$this->feed = new SimplePie();
		$this->feed->set_feed_url( $this->source );
		$this->feed->set_cache_class( 'WP_Feed_Cache' );
		$this->feed->set_file_class( 'WP_SimplePie_File' );
		$this->feed->set_cache_duration( apply_filters( 'wp_feed_cache_transient_lifetime', ( 30 * 60 ), $this->source ) );
		do_action_ref_array( 'wp_feed_options', array( &$this->feed, $this->source ) );
		$this->feed->init();
		$this->feed->handle_content_type();
	
		if ( $this->feed->error() ) {
			error_log( '[UMW Home Page]: There was an error processing the RSS feed. The error message was: ' . $this->feed->error() );
			return $this->feed = new WP_Error( 'simplepie-error', $this->feed->error() );
		}
		
		return $this->feed;
	}
	
	/**
	 * Process the requested feed items and turn them into slides
	 */
	function process_feed() {
		error_log( '[UMW Home Page]: Entered the process_feed() method' );
		$this->fetch_feed();
		if ( is_wp_error( $this->feed ) )
			return;
		
		error_log( '[UMW Home Page]: Made it past error-checking for the feed' );
		foreach( $this->feed->get_items( 0, 10 ) as $item ) {
			$tmpimg = $item->get_enclosure();
			if ( empty( $tmpimg ) || ! is_object( $tmpimg ) )
				continue;
			
			$img = array( 'src' => $tmpimg->get_link(), 'alt' => null );
			$caption = array( 'title' => $item->get_title(), 'text' => $item->get_description() );
			$link = array( 'url' => $item->get_permalink() );
			
			error_log( '[UMW Home Page]: Preparing to create a new UMW_Home_Slide object for a specific slide' );
			$this->slides[] = new UMW_Home_Slide( $img, $caption, $link );
		}
	}
	
	function script_atts() {
		echo '<!-- UMW Slider Attributes -->' . "\n" . '<script>var umw_slider_atts = ' . json_encode( $this->atts ) . ';</script>' . "\n" . '<!-- /UMW Slider Attributes -->';
	}
	
	/**
	 * Output the content of the slider
	 */
	function get_slider( $atts = array() ) {
		error_log( '[UMW Home Page]: Entered the get_slider() method' );
		
		$this->atts = $this->get_defaults( $atts );
		$defaults = $this->_defaults();
		foreach ( $defaults as $k => $v ) {
			if ( true === $v || false === $v ) {
				$this->atts[$k] = in_array( $this->atts[$k], array( 'true', true, 1, '1' ), true );
			}
		}
		$this->source = esc_url( $this->atts['feed'] );
		unset( $this->atts['feed'] );
		
		wp_enqueue_style( 'umw-slider' );
		wp_enqueue_script( 'umw-slider' );
		/*wp_localize_script( 'umw-slider', 'umw_slider_atts', $this->atts );*/
		add_action( 'wp_footer', array( $this, 'script_atts' ), 1 );
		
		if ( false !== ( $this->show = get_transient( 'umw-home-page-slider', false ) ) )
			return $this->show;
		
		error_log( '[UMW Home Page]: Not using an existing cache for the slider' );
		$this->process_feed();
		
		if ( empty( $this->slides ) && false !== ( $this->show = get_option( 'umw-home-page-slider-cache', true ) ) )
			return $this->show;
		
		$rt = '
	<div class="flexslider">
		<ul class="slides">';
		if ( empty( $this->slides ) ) {
			$rt .= '
			<li class="slide">
				<article class="slide-content">
					<section class="slide-caption">
						<h1>' . __( 'No Content Found' ) . '</h1>
						<p>' . __( 'Unfortunately, no content for this slideshow could be found. Please check back later.' ) . '</p>
					</section>
				</article>
			</li>';
			$rt .= '
		</ul>
	</div>';
			
			return $rt;
		}
		
		foreach( $this->slides as $slide )
			$rt .= $this->slide( $slide );
		
			$rt .= '
		</ul>
	</div>';
			
			$this->show = $rt;
			set_transient( 'umw-home-page-slider', $rt, ( 60 * 30 ) );
			update_option( 'umw-home-page-slider-cache', $rt );
			
			return $rt;
	}
	
	/**
	 * Output the slider
	 */
	function slider( $atts = array() ) {
		error_log( '[UMW Home Page]: Entered the slider() method' );
		echo $this->get_slider( $atts );
	}
	
	function slide( $slide ) {
		if ( ! is_object( $slide ) )
			return error_log( '[UMW Home Page]: For some reason, the slide in the slide() method was not an object' );
		$rt = '
	<li class="slide">
		<article class="slide-content">';
		if ( ! empty( $slide->link->url ) )
			$rt .= '<a href="' . esc_url( $slide->link->url ) . '">';
			
		$rt .= '
			<img src="' . $slide->img->src . '" alt="' . $slide->img->alt . '" />';
		
		if ( ! empty( $slide->link->url ) )
			$rt .= '</a>';
		
		if ( ! empty( $slide->caption->title ) || ! empty( $slide->caption->text ) ) {
			$rt .= '
			<section class="slide-caption">';
			if ( ! empty( $slide->caption->title ) ) {
				$rt .= '
				<h1>' . ( empty( $slide->link->url ) ? '' : '<a href="' . esc_url( $slide->link->url ) . '">' ) . apply_filters( 'the_title', $slide->caption->title ) . ( empty( $slide->link->url ) ? '' : '</a>' ) . '</h1>';
			}
			if ( ! empty( $slide->caption->text ) ) {
				$rt .= $slide->caption->text;
			}
			$rt .= '
			</section>';
		}
		$rt .= '
		</article>
	</li>';
		
		return $rt;
	}
}
