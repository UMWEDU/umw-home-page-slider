<?php
class UMW_Home_Slider_Widget extends WP_Widget {
	function __construct() {
	}
	
	function form( $instance ) {
	}
	
	function update( $new_instance, $old_instance ) {
	}
	
	function widget( $args, $instance ) {
		global $umw_home_page_slideshow_obj;
		$title = empty( $instance['title'] ) ? null : $args['before_title'] . esc_attr( $instance['title'] ) . $args['after_title'];
		echo $instance['before_widget'];
		echo $title;
		$umw_home_page_slideshow_obj->slider();
		echo $instance['after_widget'];
	}
}

class UMW_Home_Slide {
	var $img => null;
	var $caption => null;
	var $link = null;
	
	function __construct( $img=array(), $caption=array(), $link=array() ) {
		$this->img = (object) array( 'src' => null, 'alt' => null );
		$this->caption = (object) array( 'title' => null, 'text' => null );
		$this->link = (object) array( 'url' => null );
		
		if ( is_array( $img ) )
			$this->img = (object)$img;
		if ( is_array( $caption ) )
			$this->caption = (object)$caption;
		if ( is_array( $link ) )
			$this->link = (object)$link;
		
		$this->caption->text = strip_tags( apply_filters( 'the_content', $this->caption->text ) );
		
		if ( str_word_count( $this->caption->text ) > 25 ) {
			$tmp = explode( ' ', $this->caption->text );
			$tmp = implode( ' ', array_slice( $tmp, 0, 24 ) );
			$this->caption->text = $tmp . '&hellip;';
		}
	}
}

class UMW_Home_Page_Slideshow {
	var $source = 'http://feeds.feedburner.com/umw-greatminds-home/';
	var $feed = null;
	var $slides = array();
	var $show = null;
	
	/**
	 * Construct the UMW Home Page Slideshow object
	 */
	function __construct() {
		add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_scripts' ), 99 );
	}
	
	/**
	 * Register the scripts and styles used in this plugin
	 */
	function enqueue_scripts() {
		if ( ! wp_script_is( 'flexslider', 'registered' ) )
			wp_register_script( 'flexslider', plugins_url( 'scripts/jquery.flexslider.min.js', __FILE__ ), array( 'jquery' ), '2.1', true );
		if ( ! wp_style_is( 'flexStyles', 'registered' ) )
			wp_register_style( 'flexStyles', plugins_url( 'styles/jquery.flexslider.css', __FILE__ ), array(), '2.1', true );
		
		wp_register_style( 'umw-slider', plugins_url( 'styles/umw-slider.css', __FILE__ ), array( 'flexStyles' ), '0.1', 'all' );
		wp_register_script( 'umw-slider', plugins_url( 'scripts/umw-slider.js', __FILE__ ), array( 'flexslider' ), '0.1', true );
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
		$this->feed->set_cache_duration( apply_filters( 'wp_feed_cache_transient_lifetime', 43200, $this->source ) );
		do_action_ref_array( 'wp_feed_options', array( &$this->feed, $this->source ) );
		$this->feed->init();
		$this->feed->handle_content_type();
	
		if ( $this->feed->error() ) {
			return $this->feed = new WP_Error( 'simplepie-error', $this->feed->error() );
		}
		
		return $this->feed;
	}
	
	/**
	 * Process the requested feed items and turn them into slides
	 */
	function process_feed() {
		$this->fetch_feed();
		if ( is_wp_error( $this->feed ) )
			return;
		
		foreach( $this->feed->get_items( 0, 10 ) as $item ) {
			$tmpimg = $item->get_enclosure();
			if ( empty( $tmpimg ) || ! is_object( $tmpimg ) )
				continue;
			
			$img = array( 'src' => $tmpimg->get_link(), 'alt' => null );
			$caption = array( 'title' => $item->get_title(), 'text' => $item->get_description() );
			$link = array( 'url' => $item->get_permalink() );
			
			$this->slides[] = new UMW_Home_Slide( $img, $caption, $link );
		}
	}
	
	/**
	 * Output the content of the slider
	 */
	function get_slider() {
		if ( false !== ( $this->show = get_transient( 'umw-home-page-slider', false ) ) )
			return $this->show;
		
		$this->process_feed();
		wp_enqueue_style( 'umw-slider' );
		wp_enqueue_script( 'umw-slider' );
		
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
			
			$this->show = $rt;
			set_transient( 'umw-home-page-slider', $rt, ( 60 * 30 ) );
			update_option( 'umw-home-page-slider-cache', $rt );
			
			return $rt;
		}
		
		foreach( $this->slides as $slide )
			$rt .= $this->slide( $slide );
		
			$rt .= '
		</ul>
	</div>';
			
			return $rt;
	}
	
	/**
	 * Output the slider
	 */
	function slider() {
		echo $this->get_slider();
	}
	
	function slide( $slide ) {
		if ( ! is_object( $slide ) )
			return;
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
		}
		$rt .= '
		</article>
	</li>';
	}
}

function inst_umw_home_page_slideshow() {
	global $umw_home_page_slideshow_obj;
	$umw_home_page_slideshow_obj = new UMW_Home_Page_Slideshow;
}
inst_umw_home_page_slideshow();

add_action( 'widgets_init', 'inst_umw_home_page_slideshow_widget' );
function inst_umw_home_page_slideshow_widget() {
	register_widget( 'UMW_Home_Slider_Widget' );
}