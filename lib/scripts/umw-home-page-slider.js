/**
 * UMW Home Page Slideshow Scripts
 * @package UMW Home Page Slider
 * @version 0.1.33
 */

jQuery( function($) {
    function moveSliderButtons() {
        var h = $( '.home-slider-container .flex-active-slide picture img' ).innerHeight();
        var capMar = $( '.home-slider-container .caption' ).css( 'margin-top' );
        capMar = capMar.replace( /px/g, '' );
        capMar = ( capMar * 1 );

        var sliderPad = $( '.home-slider-container' ).css( 'padding-top' );
        sliderPad = sliderPad.replace( /px/g, '' );
        sliderPad = ( sliderPad * 1 );

        if( $( '.landing-flex-nav .screen-reader-text' ).innerWidth() > 1 ) {
            if( capMar <= 0 ) {
                $( '.home-slider-container .caption' ).css( 'margin-top', '48px' );
            }
            if ( sliderPad != 0 ) {
                $( '.home-slider-container' ).css( 'padding-top', 0 );
            }

            $( '.home-slider-container .landing-flex-nav .dashicons' ).css( 'top', h + 'px' );
        } else {
            if( capMar > 0 ) {
                $( '.home-slider-container .caption' ).css( 'margin-top', 0 );
            }
            if ( sliderPad != 0 ) {
                $( '.home-slider-container' ).css( 'padding-top', 0 );
            }
        }

        return false;
    };
    umw_slider_atts.start = function() { return moveSliderButtons(); };
    if ( true === umw_slider_atts.directionNav ) {
    	console.log( 'The directionNav attribute is true, so we are setting a custom direction nav' );
		umw_slider_atts.customDirectionNav = '.landing-flex-nav a';
	}
    jQuery( '.flexslider' ).flexslider( umw_slider_atts );
} );
