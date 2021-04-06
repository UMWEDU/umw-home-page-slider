/**
 * UMW Home Page Slideshow Scripts
 * @package UMW Home Page Slider
 * @version 0.1.33
 */

var umwSliderTestFunction;

jQuery( function($) {
    function moveSliderButtons() {
        var h = $( '.home-slider-container .flex-active-slide img' ).innerHeight();
        console.log( 'The inner height of the active slide is: ' + h );
        var capMar = $( '.home-slider-container .slide-caption' ).css( 'margin-top' );
        console.log( 'The margin-top of the sldie caption is: ' + capMar );
        capMar = capMar.replace( /px/g, '' );
        capMar = ( capMar * 1 );
        console.log( 'After processing, the margin-top of the slide caption is: ' + capMar );

        var sliderPad = $( '.home-slider-container' ).css( 'padding-top' );
        sliderPad = sliderPad.replace( /px/g, '' );
        sliderPad = ( sliderPad * 1 );
        console.log( 'The padding-top of the container is: ' + sliderPad );

        if( $( '.landing-flex-nav .screen-reader-text' ).innerWidth() > 1 ) {
            console.log( 'This appears to be a mobile screen, so we are adjusting the position of the nav buttons' );
            if( capMar <= 0 ) {
                console.log( 'The margin-top of the slide caption is less than 0' );
                $( '.home-slider-container .slide-caption' ).css( 'margin-top', '48px' );
            }
            if ( sliderPad != 0 ) {
                console.log( 'The padding-top of the container is not 0' );
                $( '.home-slider-container' ).css( 'padding-top', 0 );
            }

            console.log( 'Preparing to set the top proprety of the nav items to ' + h );
            $( '.home-slider-container .landing-flex-nav .dashicons' ).css( 'top', h + 'px' );
        } else {
            if( capMar > 0 ) {
                $( '.home-slider-container .slide-caption' ).css( 'margin-top', 0 );
            }
            if ( sliderPad != 0 ) {
                $( '.home-slider-container' ).css( 'padding-top', 0 );
            }
        }

        return false;
    };

    umwSliderTestFunction = moveSliderButtons;

    umw_slider_atts.start = function() { return moveSliderButtons(); };
    jQuery( document ).on( 'resize', moveSliderButtons() );
    if ( umw_slider_atts.directionNav === true ) {
    	console.log( 'The directionNav attribute is true, so we are setting a custom direction nav' );
		umw_slider_atts.customDirectionNav = '.landing-flex-nav a';
	}
    jQuery( '.flexslider' ).flexslider( umw_slider_atts );
} );
