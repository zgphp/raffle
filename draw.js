function random_attendee() {
    var count = $(".attendee").length;
    var random = Math.floor((Math.random() * count));
    return $(".attendee:eq(" + random + ")");
}

function draw(count) {
    $(".attendee").removeClass('highlight');
    var attendee = random_attendee();
    attendee.addClass('highlight');
    
    if (count > 0) {
        setTimeout(function() {
            draw(count - 1)
        }, 100);
    }
}

$(document).ready(function() {
    $("#draw_button").click(function() {
        draw(20);
    });
});
