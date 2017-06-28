function cardResize(ratio) {
    var card = $('.cardResizer');
    var width = parseFloat(card.css('width'));
    var height = width * ratio;
    card.css('height', height);
};

cardResize(1);


$(document).ready( function () {

    $(window).resize(function () {
        cardResize(1);
    });

    $(".button-collapse").sideNav();
    $('.slider').slider();
    $('#moreLink').click( function () {
        $('.container_footer').slideToggle(500);
    });

// init Isotope
    var $grid = $('.cards').isotope({
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
        $grid.isotope({ filter: filterValue });
    });

// change is-checked class on buttons
    $('.buttons').each( function( i, buttonGroup ) {
        var $buttonGroup = $(buttonGroup);
        $buttonGroup.on( 'click', 'button', function() {
            $('.active_button').removeClass('active_button');
            var btnClass = $(this).attr('data-filter');
            $('button[data-filter="' + btnClass + '"]').addClass('active_button');
        });
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

    })

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
});

