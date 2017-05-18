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
        itemSelector: '.card',
        layoutMode: 'fitRows'
    });

// filter functions
    var filterFns = {};
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
            $buttonGroup.find('.active_button').removeClass('active_button');
            $(this).addClass('active_button');
        });
    });

});

