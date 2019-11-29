$(document).ready(function() {

    const twitch = window.Twitch.ext;

    twitch.onContext(function(context) {
        if (context.theme == 'light') {
            $('body').css("background-color", "#6441A4");
            $('body').css("color", "#FFFFFF");
        }
        if (context.theme == 'dark') {
            $('body').css("background-color", "#19171c");
            $('body').css("color", "#FFFFFF");
        }
    });

    $(".alert").click(function() {
        $(this).hide("slow");
    })

});