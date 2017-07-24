function cardResize() {
    var width = parseFloat($('.card').css('width'))
    $('.card').css('height', width);

    var width = parseFloat($('.resizer').css('width'))
    $('.resizer').css('height', width);
}

$(document).ready( function () {

    cardResize();

    $(window).resize(function () {
        cardResize();
    });


    $('.slider').slider({
        'transition': 300,
        'interval' : 3000
    });

    $('#moreLink').click( function () {
        $('.container_footer').slideToggle(500);
    });

// init Isotope
    var $grid = $('.cardz').isotope({
        itemSelector: '.iso_item',
        layoutMode: 'fitRows'
    });



// filter functions
    var filterFns = {};
    //filter 'Tous' on load
    var filterValue = '.Tous';
    // use filterFn if matches value
    filterValue = filterFns[ filterValue ] || filterValue;
    $grid.isotope({ filter: filterValue });
// bind filter button click
    $('.filters-button-group').on( 'click', 'button', function() {
        var filterValue = $( this ).attr('data-filter');
        // use filterFn if matches value
        filterValue = filterFns[ filterValue ] || filterValue;
        cardResize();
        $grid.isotope({ filter: filterValue });
    });

// change is-checked class on buttons
    $('.buttons').each( function( i, buttonGroup ) {
        var $buttonGroup = $(buttonGroup);
        $buttonGroup.on( 'click', 'button', function() {
            $('.active_button').removeClass('active_button');
            var btnClass = $(this).attr('data-filter');
            $('button[data-filter="' + btnClass + '"]').addClass('active_button');
        })
    });

    // carousel SLICK -- Work's page
    $('.slider-for').slick({
        slidesToShow: 1,
        slidesToScroll: 1,
        arrows: false,
        fade: true,
        asNavFor: '.slider-nav'
    });

    $('.slider-nav').slick({
        slidesToShow: 5,
        slidesToScroll: 1,
        asNavFor: '.slider-for',
        dots: true,
        centerMode: false,
        focusOnSelect: true,
        variableWidth: true
    });

    // Show "add favs" button on mouse hover card-image
    $('.card-image').hover( function () {
        $(this).find('.fav_btn, .fav_btn_delete').slideDown(200);
    }, function () {
        $(this).find('.fav_btn, .fav_btn_delete').slideUp(200);

    });

    // Show Form User on profil's user page
    $('#showFormUser').click( function (e) {
        e.preventDefault();
        var form = $(this).attr('href');
        $(form).slideToggle(200);
    });

    // Materialize Modal
    $('.modal').modal();

    // Materialize Tabs
    $('ul.tabs').tabs();

    // Materialize textarea
    $('textarea').trigger('autoresize');

    // Materialize Select
    $('select').material_select();

    // Materialize SideNav
    $(".button-collapse").sideNav({
        menuWidth: 300, // Default is 300
        edge: 'left', // Choose the horizontal origin
        draggable: true, // Choose whether you can drag to open on touch screens,
    });

    /*** PAGE ACTU ***/
    /* Code ci-dessous appliqué uniquement si sur page actu (voir regexp) */
    var actu = /\/actu/;

    if(actu.test(window.location.href)){

        if($(window).width() > 1024){

            /*** Functions on top FIRST ! ***/

            /* Let's change cursor + add some zoom effect
             *  when hovering over the picture
             */
            function showPointer(){
                $(this).css({
                    'cursor' : 'pointer',
                    'transform' : 'scale(1.1)',
                    '-moz-transform' : 'scale(1.1)',
                    '-webkit-transform' : 'scale(1.1)',
                    '-moz-transition' : '.5s',
                    '-webkit-transition' : '.5s'
                });
            }

            /* If mouseleave, we get the picture back to normal scale */
            function unZoom(){
                $(this).css({
                    'transform' : 'scale(1)',
                    '-moz-transform' : 'scale(1)',
                    '-webkit-transform' : 'scale(1)'
                });
            }

            /* Let's display a bigger picture */
            function zoomIn(){
                $(this).off('click', zoomIn);
                $(this).off('mouseover', showPointer);
                $(this).off('mouseleave', unZoom);

                $figureElt = $(this).parent();
                $img = $(this);
                $dezoom = $(this).parent().find('.dezoom');

                $bodyElt.css("overflow" , "hidden");
                $figureElt.addClass('figure-zoom');
                $img.addClass('image-zoomed-in');

                $img.css({
                    'cursor' : 'initial',
                    'transform' : 'scale(1)',
                    '-moz-transform' : 'scale(1)',
                    '-webkit-transform' : 'scale(1)',
                    'transition' : '0s',
                    '-moz-transition' : '0s',
                    '-webkit-transition' : '0s'
                });

                $dezoom.click(function(){
                    $bodyElt.css("overflow" , "visible");
                    $figureElt.removeClass('figure-zoom');
                    $img.removeClass('image-zoomed-in');
                    $img.css('cursor' , 'pointer');
                    $img.on("click", zoomIn);
                    $img.on("mouseover", showPointer);
                    $img.on("mouseleave", unZoom);
                });
            }

            /* Selectors */
            var $figureElts = $('.figure-no-zoom img');
            var $bodyElt = $('body');

            /*** Functions executed depending on the event ***/
            $figureElts.on("mouseover", showPointer);
            $figureElts.on("mouseleave", unZoom);
            $figureElts.on("click", zoomIn);

            $footer = $('footer');
            $container = $('.container').first();
            $offsetTop = $container.position().top + $container.height() + 100;
            $footer.css('top', $offsetTop + 'px');
        }
    }

    $('#alert-newsletter-subscription').delay(2000).fadeOut(2000);

    $("input:file").on("change", function(){
        $("#images-check").children().remove();

        $img = new Image();
        $img.src = window.URL.createObjectURL($(this)[0].files[0]);

        $buttonElt = $("<button class='btn waves-effect black'>Supprimer</button>");

        $("#images-check").append($img, $buttonElt);

        $buttonElt.on("click", function(){
            $("#images-check").children().remove();
        });
    });
});

