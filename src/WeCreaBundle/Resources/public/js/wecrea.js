$(document).ready( function () {
    $(".button-collapse").sideNav();
    $('.slider').slider();
    $('#moreLink').click( function () {
        $('.container_footer').slideToggle(500);
    });
});
