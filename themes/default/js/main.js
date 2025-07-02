(function($) {
    "use strict";
    $('ul.tcm li.father').click(function(){
        $(this).find('ul').slideToggle();
    });

    $(window).scroll(function () {
        if ($(this).scrollTop() > 300) {
            $('.back-top').fadeIn();
        } else {
            $('.back-top').fadeOut();
        }
    });
    $('#link').change(function(event) {
        event.preventDefault();
        window.location = $(this).val();
    });
    // scroll body to 0px on click
    $(document).on('click','.back-top', function () {
        $('body,html').animate({
            scrollTop: 0
        }, 500);
        return false;
    });

    $(document).ready(function() {
        
        $('li.sub a').each(function() {
            if($(this).attr('href') === CNV.categoryActive) {
                $(this).parent().addClass('active');
                $(this).parent().parent().toggle();
            }
        });

        $('.f18').each(function() {
            if($(this).attr('href') === CNV.categoryActive) {
                $(this).addClass('active');
                $(this).parent().addClass('active');
                $(this).parent().find('ul').toggleClass('open');
            }
        });

        $( '.video-wd-more' ).owlCarousel({
            nav: true,
            navText: ['<i class="fa fa-angle-left"></i>', '<i class="fa fa-angle-right"></i>'],
            dots: false,
            margin: 16,
            responsive:{
                0:{
                    items:1
                },
                415:{
                    items: 2
                },
                541:{
                    items: 2
                },
                768:{
                    items:3
                },

                992:{
                    items:2
                },
                1200:{
                    items:3
                }
            }
        });

        $( '.first-news .slider' ).owlCarousel({
            items: 1,
            nav: false,
            dots: false,
            loop: true,
            autoplay: true,
            autoplayTimeout:5000,
            autoplayHoverPause:true
        });

        $( '.photo-interview .slider' ).owlCarousel({
            nav: true,
            navText: ['<i class="fa fa-caret-left"></i>', '<i class="fa fa-caret-right"></i>'],
            dots: false,
            margin: 16,
            responsive:{
                0:{
                    items:2
                },
                415:{
                    items: 2
                },
                541:{
                    items: 3
                },
                768:{
                    items:4
                },

                992:{
                    items:4
                },
                1200:{
                    items:4
                }
            }
        });

        $( '.featured-newest.owl-carousel' ).owlCarousel({
            items: 1,
            nav: true,
            navText: ['<i class="fa fa-caret-left"></i>', '<i class="fa fa-caret-right"></i>'],
            dots: false,
            margin: 1,
        });

        $('.featured-news-sliders .bxslider').bxSlider({
            mode: 'vertical',
            minSlides: 4,
            slideMargin: 25,
            speed: 1000,
            pager: false,
            controls: true,
            nextText: '<i class="fa fa-caret-up"></i>',
            prevText: '<i class="fa fa-caret-down"></i>'
        });

        $( 'ul.list-title' ).each(function(index, el) {
            $( this ).wrap('<div class="list-title-wrap"></div>');
            var firstText = $( this ).find( 'li:first-child a' ).text();
            $( this ).parent().prepend('<div class="list-lable">' + firstText + '</div>');          
        });
        $( '.list-lable' ).on( 'click', function() {
            $( this ).parent().find( 'ul' ).slideToggle(400);
        });

        //mobile menu
        $( '.mobile-menu' ).on( 'click', function() {
            $( this ).parents( '.main-menu' ).toggleClass('open');
        });
        $( '.sub-menu' ).each(function() {
            $( this ).parent().addClass( 'has-child' ).append( '<span class="arrow"><i class="fa fa-caret-down"></i></span>' );
        });
        $( '.main-menu .arrow' ).on( 'click', function(e) {
            e.preventDefault();
            $( this ).parents( 'li' ).find( '> .sub-menu' ).toggleClass( 'open' );
        });
        $('.main-menu .menu-main .has-child > a').on('click', function(){

        })
    });
})(jQuery);