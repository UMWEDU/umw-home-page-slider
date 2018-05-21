<?php
/*
Plugin Name: UMW Home Page Slider
Plugin URI: http://www.umw.edu/
Description: Implements the slideshow used on the UMW home page.
Version: 0.1a
Author: Curtiss Grymala
Author URI: http://www.umw.edu/
License: GPL2
*/

/**
 * Set up an autoloader to automatically pull in the appropriate taxonomy class definitions
 *
 * @param string $class_name the full name of the class being invoked
 *
 * @since 2018.1
 * @return void
 */
spl_autoload_register( function ( $class_name ) {
	if ( ! stristr( $class_name, 'UMW\Home_Slider\\' ) ) {
		return;
	}

	$filename = plugin_dir_path( __FILE__ ) . 'lib/classes/' . strtolower( str_replace( array(
			'\\',
			'_'
		), array( '/', '-' ), $class_name ) ) . '.php';
	if ( ! file_exists( $filename ) ) {
		return;
	}

	include_once $filename;
} );

/**
 * Instantiate a \UMW\Home_Sliders\Slideshow object
 * @uses global $umw_home_page_slideshow_obj
 */
function inst_umw_home_page_slideshow() {
	global $umw_home_page_slideshow_obj;
	if ( isset( $umw_home_page_slideshow_obj ) && is_a( $umw_home_page_slideshow_obj, 'UMW_Home_Page_Slideshow' ) ) {
		return;
	}

	$umw_home_page_slideshow_obj = new \UMW\Home_Slider\Slideshow;
}

/*add_action( 'plugins_loaded', 'inst_umw_home_page_slideshow' );*/

/**
 * Register the widget for the UMW Home Page Slideshow plugin
 * @uses register_widget()
 */
function inst_umw_home_page_slideshow_widget() {
	register_widget( '\UMW\Home_Slider\Widget' );
}

add_action( 'widgets_init', 'inst_umw_home_page_slideshow_widget' );