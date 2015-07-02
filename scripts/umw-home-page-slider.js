/**
 * UMW Home Page Slideshow Scripts
 * @package UMW Home Page Slider
 * @version 0.1.31
 */

jQuery( function() {
	umw_slider_atts.start = function() {
			var slideHeight = jQuery( '.flexslider .slide' ).first().innerHeight();
			var slideWidth = jQuery( '.flexslider .slide' ).first().outerWidth();
			jQuery( '.flexslider .slide-caption' ).each( function() {
				var captionHeight = jQuery( this ).outerHeight();
				var newHeight = ( slideHeight - captionHeight ) / 2;
				newHeight = ( newHeight / slideHeight ) * 100;
				newHeight = ( slideHeight / slideWidth ) * newHeight;
				/*console.log( 'slideHeight: ' + slideHeight );
				console.log( 'slideWidth: ' + slideWidth );
				console.log( 'captionHeight: ' + captionHeight );
				console.log( 'newHeight: ' + newHeight );
				newHeight = 100;*/
				if ( newHeight > 0 ) {
					jQuery( this ).css({ 'margin-top' : newHeight + '%' });
				}
			} );
		};
		jQuery( '.flexslider' ).flexslider( umw_slider_atts );
	/*umw_slider_atts.sync = '.uhp-slider-nav';
	umw_slider_atts.controlNav = false; 
	
	var umw_control_nav_atts = {
		'animation' : 'slide', 
		'controlNav' : false, 
		'animationLoop' : true, 
		'slideshow' : false, 
		'itemWidth' : 35, 
		'itemMargin' : 5,
		'asNavFor' : '.uhp-slider'
	};
	
	umw_control_nav_atts.randomize = umw_slider_atts.randomize;
	
	jQuery( '.uhp-slider-nav' ).flexslider( umw_control_nav_atts );
	jQuery( '.uhp-slider' ).flexslider( umw_slider_atts );*/
} );
