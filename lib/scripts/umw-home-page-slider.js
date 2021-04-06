/**
 * UMW Home Page Slideshow Scripts
 * @package UMW Home Page Slider
 * @version 0.1.33
 */

jQuery(function ($) {
    function moveSliderButtons() {
        var slider = jQuery('.home-slider-container .flexslider');
        var h = $('.home-slider-container .flex-active-slide img').innerHeight();
        console.log('The inner height of the active slide is: ' + h);
        var capMar = getComputedStyle(document.querySelectorAll('.home-slider-container .slide-caption')[0]).getPropertyValue('margin-top');
        console.log('The margin-top of the slide caption is: ' + capMar);
        capMar = capMar.replace(/px/g, '');
        capMar = (capMar * 1);
        console.log('After processing, the margin-top of the slide caption is: ' + capMar);

        var sliderPad = getComputedStyle(document.querySelectorAll('.home-slider-container .flexslider')[0]).getPropertyValue('padding-top');
        sliderPad = sliderPad.replace(/px/g, '');
        sliderPad = (sliderPad * 1);
        console.log('The padding-top of the container is: ' + sliderPad);

        if ($('.landing-flex-nav .screen-reader-text').innerWidth() > 1) {
            if (jQuery(slider).hasClass('is-mobile')) {
                return;
            }

            console.log('This appears to be a mobile screen, so we are adjusting the position of the nav buttons');

            console.log('The margin-top of the slide caption is less than 0');
            $('.home-slider-container a img').css('padding-bottom', '48px');

            console.log('The padding-top of the container is not 0');
            $('.home-slider-container .flexslider').css('padding-top', 0);

            console.log('Preparing to set the top proprety of the nav items to ' + h);
            $('.home-slider-container .landing-flex-nav .dashicons').css('top', h + 'px');

            jQuery(slider).addClass('is-mobile');
        } else if (jQuery(slider).hasClass('is-mobile')) {
            jQuery(slider).removeClass('is-mobile');

            $('.home-slider-container a img').css('padding-bottom', 0);
            $('.home-slider-container .flexslider').css('padding-top', 0);
        }

        return false;
    };

    umwSliderTestFunction = moveSliderButtons;

    umw_slider_atts.start = function () {
        return moveSliderButtons();
    };
    jQuery(window).resize(function () {
        return moveSliderButtons();
    });
    if (umw_slider_atts.directionNav === true) {
        console.log('The directionNav attribute is true, so we are setting a custom direction nav');
        umw_slider_atts.customDirectionNav = '.landing-flex-nav a';
    }
    jQuery('.flexslider').flexslider(umw_slider_atts);
});
