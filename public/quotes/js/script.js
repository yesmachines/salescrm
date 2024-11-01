$('#top').click(function () {
    $('html,body').animate({
        scrollTop: 0
    }, 900);
    return false;
});
$('#home').click(function () {
    $('html, body').animate({
        scrollTop: $('#homepage').offset().top
    }, 800);
    return false;
});

$('#about').click(function () {
    $('html, body').animate({
        scrollTop: $('#aboutpage').offset().top
    }, 800);
    return false;
});

$('#artperson').click(function () {
    $('html, body').animate({
        scrollTop: $('#gridcontainer').offset().top
    }, 800);
    return false;
});

$('#gallery').click(function () {
    $('html, body').animate({
        scrollTop: $('#gallerypage').offset().top
    }, 800);
    return false;
});
$('#source').click(function () {
    $('html, body').animate({
        scrollTop: $('#sourcespage').offset().top
    }, 800);
    return false;
});


// ===========================  Grid and List View Start ===============================

var $cardContainer = $('.download-cards');
var $downloadCard = $('.download-card__content');
var $imageBox = $('.download-card__image')
var $viewTrigger = $('button').attr('data', 'trigger');

function loadImages() {
    $imageBox.each(function () {
        var url = $(this).attr('data-image');
        $(this)
            .css('background-image', 'url(' + url + ')')
            .removeAttr('data-image');
    })
}

function swapTriggerActiveClass(e) {
    $viewTrigger.removeClass('active');
    $(e.target).addClass('active');
}

function swapView(e) {
    var $currentView = $(e.target).attr('data-trigger');
    $cardContainer.attr('data-view', $currentView);
}

$(document).ready(function () {
    loadImages();
    $viewTrigger.click(function (e) {
        swapTriggerActiveClass(e);
        swapView(e);
    });
});

// ===========================  Grid and List View end ===============================





// ===========================  blog start ===============================
// Params
var sliderSelector = '.swiper-container',
    options = {
        init: false,
        loop: true,
        speed: 800,
        slidesPerView: 2, // or 'auto'
        // spaceBetween: 10,
        centeredSlides: true,
        effect: 'coverflow', // 'cube', 'fade', 'coverflow',
        coverflowEffect: {
            rotate: 50, // Slide rotate in degrees
            stretch: 0, // Stretch space between slides (in px)
            depth: 100, // Depth offset in px (slides translate in Z axis)
            modifier: 1, // Effect multipler
            slideShadows: true, // Enables slides shadows
        },
        grabCursor: true,
        parallax: true,
        pagination: {
            el: '.swiper-pagination',
            clickable: true,
        },
        navigation: {
            nextEl: '.swiper-button-next',
            prevEl: '.swiper-button-prev',
        },
        breakpoints: {
            1023: {
                slidesPerView: 1,
                spaceBetween: 0
            }
        },
        // Events
        on: {
            imagesReady: function () {
                this.el.classList.remove('loading');
            }
        }
    };
var mySwiper = new Swiper(sliderSelector, options);

// Initialize slider
mySwiper.init();
// ===========================  blog end ===============================