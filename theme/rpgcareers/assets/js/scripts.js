function debounce(func, wait, immediate) {
    var timeout;
    return function() {
        var context = this, args = arguments;
        var later = function() {
            timeout = null;
            if (!immediate) func.apply(context, args);
        };
        var callNow = immediate && !timeout;
        clearTimeout(timeout);
        timeout = setTimeout(later, wait);
        if (callNow) func.apply(context, args);
    };
};

$(document).ready(function () {

    $('.slider').slick({
        infinite: true,
        dots: true,
        slidesToShow: 1,
        dotsClass: 'slider__nav',
        prevArrow: '<button type="button" class="slider__prev">Previous</button>',
        nextArrow: '<button type="button" class="slider__next">Next</button>',
        autoplay: true,
        autoplaySpeed: 5000
    });

    var navClosedHtml = '<span class="sr-only">Open </span>menu';
    var navOpenHtml = 'Close <span class="sr-only"> Menu</span>';
    var navInitialized = false;
    var navOpen = false;
    var navEl = $('#nav');
    var navToggle = $('<button>').attr({
        'aria-expanded': false,
        'aria-controls': 'nav',
        'class': 'masthead__toggle'
    }).html(navClosedHtml);

    navToggle.on('click', function (event) {
        event.preventDefault();
        navOpen = !navOpen;
        navToggle.attr('aria-expanded', navOpen).html(navOpen ? navOpenHtml : navClosedHtml);
        navEl.attr('aria-hidden', !navOpen);
        $('body').toggleClass('mobile-nav-open');
    });

    $('.mobile-overlay').on('click', function (event) {
        event.preventDefault();
        navOpen = false;
        navEl.attr('aria-hidden', true);
        $('body').removeClass('mobile-nav-open');
        navToggle.attr('aria-expanded', false).html(navClosedHtml);

    });

    checkIfNavToggle();
    $(window).on('resize', debounce(checkIfNavToggle));

    function checkIfNavToggle() {

        var mediaQuery = window.matchMedia('(min-width: 990px').matches;

        if(!mediaQuery && !navInitialized) {
            initializeMobileNav();
        }

        if(mediaQuery && navInitialized) {
            uninitializeMobileNav();
        }

        return false;
    }

    function initializeMobileNav() {
        navOpen = false;
        navInitialized = true;

        navToggle.prependTo('.masthead__nav');

        navEl.attr('aria-hidden', !navOpen);
    }

    function uninitializeMobileNav() {
        navOpen = false;
        navInitialized = false;

        navToggle.detach();

        navEl.removeAttr('aria-hidden');
    }

});