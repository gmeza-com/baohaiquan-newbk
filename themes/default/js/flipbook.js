function loadApp() {

    $('#canvas').fadeIn(1000);

    var flipbook = $('.flipbook');

    // Check if the CSS was already loaded

    if (flipbook.width()==0 || flipbook.height()==0) {
        setTimeout(loadApp, 10);
        return;
    }

    // Create the flipbook

    flipbook.turn({

            // Magazine width

            width: 922,

            // Magazine height

            height: 600,

            // Duration in millisecond

            duration: 1000,

            // Hardware acceleration

            acceleration: !isChrome(),

            // Enables gradients

            gradients: true,

            // Auto center this flipbook

            autoCenter: true,

            // Elevation from the edge of the flipbook when turning a page

            elevation: 50,

            // Events

            when: {
                turning: function(event, page, view) {

                    var book = $(this),
                    currentPage = book.turn('page'),
                    pages = book.turn('pages');

                    // Update the current URI

                    Hash.go('page/' + page).update();

                    // Show and hide navigation buttons

                    disableControls(page);


                    $('.thumbnails .page-'+currentPage).
                        parent().
                        removeClass('current');

                    $('.thumbnails .page-'+page).
                        parent().
                        addClass('current');



                },

                turned: function(event, page, view) {

                    disableControls(page);

                    $(this).turn('center');

                    if (page==1) {
                        $(this).turn('peel', 'br');
                    }

                },

                missing: function (event, pages) {

                    // Add pages that aren't in the magazine

                    for (var i = 0; i < pages.length; i++)
                        addPage(pages[i], $(this));

                }
            }

    });

    $("#prev").click(function(e){
        e.preventDefault();
        flipbook.turn("previous");
    });
    $("#next").click(function(e){
        e.preventDefault();
        flipbook.turn("next");
    });

    $('#zoomOut').click(function(){
        $('.flipbook-viewport').zoom('zoomOut');
    });
    $('#zoomIn').click(function(){
        $('.flipbook-viewport').zoom('zoomIn');
    });

    // Zoom.js

    $('.flipbook-viewport').zoom({
        flipbook: $('.flipbook'),

        max: function() {
            return largeMagazineWidth()/($('.flipbook').width() - 300);
        },

        when: {

            swipeLeft: function() {

                $(this).zoom('flipbook').turn('next');

            },

            swipeRight: function() {

                $(this).zoom('flipbook').turn('previous');

            },

            resize: function(event, scale, page, pageElement) {

                if (scale==1)
                    loadSmallPage(page, pageElement);
                else
                    loadLargePage(page, pageElement);

            },

            zoomIn: function () {

                $('.thumbnails').hide();
                $('.made').hide();
                $('.flipbook').removeClass('animated').addClass('zoom-in');
                $('.zoom-icon').removeClass('zoom-icon-in').addClass('zoom-icon-out');

                if (!window.escTip && !$.isTouch) {
                    escTip = true;

                    $('<div />', {'class': 'exit-message'}).
                        html('<div>Press ESC to exit</div>').
                            appendTo($('body')).
                            delay(2000).
                            animate({opacity:0}, 500, function() {
                                $(this).remove();
                            });
                }
            },

            zoomOut: function () {

                $('.exit-message').hide();
                $('.thumbnails').fadeIn();
                $('.made').fadeIn();
                $('.zoom-icon').removeClass('zoom-icon-out').addClass('zoom-icon-in');

                setTimeout(function(){
                    $('.flipbook').addClass('animated').removeClass('zoom-in');
                    resizeViewport();
                }, 0);

            }
        }
    });

    // Zoom event

    if ($.isTouch)
        $('.flipbook-viewport').bind('zoom.doubleTap', zoomTo);
    else
        $('.flipbook-viewport').bind('zoom.tap', zoomTo);


    // Using arrow keys to turn the page

    $(document).keydown(function(e){

        var previous = 37, next = 39, esc = 27;

        switch (e.keyCode) {
            case previous:

                // left arrow
                $('.flipbook').turn('previous');
                e.preventDefault();

            break;
            case next:

                //right arrow
                $('.flipbook').turn('next');
                e.preventDefault();

            break;
            case esc:

                $('.flipbook-viewport').zoom('zoomOut');
                e.preventDefault();

            break;
        }
    });

    // URIs - Format #/page/1

    Hash.on('^page\/([0-9]*)$', {
        yep: function(path, parts) {
            var page = parts[1];

            if (page!==undefined) {
                if ($('.flipbook').turn('is'))
                    $('.flipbook').turn('page', page);
            }

        },
        nop: function(path) {

            if ($('.flipbook').turn('is'))
                $('.flipbook').turn('page', 1);
        }
    });


    $(window).resize(function() {
        resizeViewport();
    }).bind('orientationchange', function() {
        resizeViewport();
    });

    // Events for thumbnails

    $('.thumbnails').click(function(event) {

        var page;

        if (event.target && (page=/page-([0-9]+)/.exec($(event.target).attr('class'))) ) {

            $('.flipbook').turn('page', page[1]);
        }
    });

    $('.thumbnails li').
        bind($.mouseEvents.over, function() {

            $(this).addClass('thumb-hover');

        }).bind($.mouseEvents.out, function() {

            $(this).removeClass('thumb-hover');

        });

    if ($.isTouch) {

        $('.thumbnails').
            addClass('thumbanils-touch').
            bind($.mouseEvents.move, function(event) {
                event.preventDefault();
            });

    } else {

        $('.thumbnails ul').mouseover(function() {

            $('.thumbnails').addClass('thumbnails-hover');

        }).mousedown(function() {
            return false;

        }).mouseout(function() {

            $('.thumbnails').removeClass('thumbnails-hover');

        });

    }


    // Regions

    if ($.isTouch) {
        $('.flipbook').bind('touchstart', regionClick);
    } else {
        $('.flipbook').click(regionClick);
    }

    // Events for the next button

    $('.next-button').bind($.mouseEvents.over, function() {

        $(this).addClass('next-button-hover');

    }).bind($.mouseEvents.out, function() {

        $(this).removeClass('next-button-hover');

    }).bind($.mouseEvents.down, function() {

        $(this).addClass('next-button-down');

    }).bind($.mouseEvents.up, function() {

        $(this).removeClass('next-button-down');

    }).click(function() {

        $('.flipbook').turn('next');

    });

    // Events for the next button

    $('.previous-button').bind($.mouseEvents.over, function() {

        $(this).addClass('previous-button-hover');

    }).bind($.mouseEvents.out, function() {

        $(this).removeClass('previous-button-hover');

    }).bind($.mouseEvents.down, function() {

        $(this).addClass('previous-button-down');

    }).bind($.mouseEvents.up, function() {

        $(this).removeClass('previous-button-down');

    }).click(function() {

        $('.flipbook').turn('previous');

    });


    resizeViewport();

    $('.flipbook').addClass('animated');

}

// Zoom icon

 $('.zoom-icon').bind('mouseover', function() {

    if ($(this).hasClass('zoom-icon-in'))
        $(this).addClass('zoom-icon-in-hover');

    if ($(this).hasClass('zoom-icon-out'))
        $(this).addClass('zoom-icon-out-hover');

 }).bind('mouseout', function() {

     if ($(this).hasClass('zoom-icon-in'))
        $(this).removeClass('zoom-icon-in-hover');

    if ($(this).hasClass('zoom-icon-out'))
        $(this).removeClass('zoom-icon-out-hover');

 }).bind('click', function() {

    if ($(this).hasClass('zoom-in'))
        $('.flipbook-viewport').zoom('zoomIn');
    else if ($(this).hasClass('zoom-out'))
        $('.flipbook-viewport').zoom('zoomOut');

 });

 $('#canvas').hide();


// Load the HTML4 version if there's not CSS transform

yepnope({
    test : Modernizr.csstransforms,
    yep: ['https://baohaiquanvietnam.vn/themes/default/js/turn.js'],
    nope: ['https://baohaiquanvietnam.vn/themes/default/js/turn.html4.min.js'],
    both: ['https://baohaiquanvietnam.vn/themes/default/js/zoom.min.js', 'https://baohaiquanvietnam.vn/themes/default/js/magazine.js', 'https://baohaiquanvietnam.vn/themes/default/css/magazine.css'],
    complete: loadApp
});
