/**
 * UMW Home Page Slideshow Scripts
 * @package UMW Home Page Slider
 * @version 0.1a
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
} );
