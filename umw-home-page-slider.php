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

if ( ! class_exists( 'UMW_Home_Slider_Widget' ) )
	require_once( plugin_dir_path( __FILE__ ) . 'classes/class-umw-home-slider-widget.php' );
if ( ! class_exists( 'UMW_Home_Slide' ) )
	require_once( plugin_dir_path( __FILE__ ) . 'classes/class-umw-home-slide.php' );
if ( ! class_exists( 'UMW_Home_Page_Slideshow' ) )
	require_once( plugin_dir_path( __FILE__ ) . 'classes/class-umw-home-page-slideshow.php' );

/**
 * Instantiate a UMW_Home_Page_Slideshow object
 * @uses global $umw_home_page_slideshow_obj
 */
function inst_umw_home_page_slideshow() {
	global $umw_home_page_slideshow_obj;
	if ( isset( $umw_home_page_slideshow_obj ) && is_a( $umw_home_page_slideshow_obj, 'UMW_Home_Page_Slideshow' ) )
		return;
	
	$umw_home_page_slideshow_obj = new UMW_Home_Page_Slideshow;
}
/*add_action( 'plugins_loaded', 'inst_umw_home_page_slideshow' );*/

/**
 * Register the widget for the UMW Home Page Slideshow plugin
 * @uses register_widget()
 */
function inst_umw_home_page_slideshow_widget() {
	register_widget( 'UMW_Home_Slider_Widget' );
}
add_action( 'widgets_init', 'inst_umw_home_page_slideshow_widget' );
