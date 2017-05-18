$(document).ready( function () {
    $(".button-collapse").sideNav();
    $('.slider').slider();
    $('#moreLink').click( function () {
        $('.container_footer').slideToggle(500);
    });
    $('.grid').isotope({
        // options
        itemSelector: '.grid-item',
        layoutMode: 'fitRows'
    });git 
});

// Resize work's cards
function cardResize(ratio) {
        var card = $('.cardResizer');
        var width = parseFloat(card.css('width'));
        var height = width * ratio;
        card.css('height', height);
};

$(document).ready( function () {
    cardResize(1);
    $(window).resize(function () {
        cardResize(1);
    });
});
