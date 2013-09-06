var iterations = 20;
var winners = [];

function random_attendee() {
    var count = $(".attendee").length;
    var random = Math.floor((Math.random() * count));
    return $(".attendee:eq(" + random + ")");
}

function draw(count) {
    $(".attendee").removeClass('highlight');
    var $attendee = random_attendee();
    $attendee.addClass('highlight');

    if (count > 0) {
        setTimeout(function() {
            draw(count - 1)
        }, 100);
    } else {
        win($attendee);
    }
}

function win($attendee) {
    var win_id = '#win-' + $attendee.attr('id');
    $(win_id).modal('show');

    // Only one win per attendee
    $attendee.remove();
}

$(document).ready(function() {
    $("#draw_button").click(function() {
        draw(20);
    });
});

$(document).keypress(function(e) {
    if (e.which == 32) {
        draw(iterations);
    }
});
