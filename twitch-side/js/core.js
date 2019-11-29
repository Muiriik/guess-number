var twitchUserId = '';
var channelId = '';

const twitch = window.Twitch.ext;

twitch.onAuthorized(function(auth) {
    saveUserId(auth);
    // twitch.rig.log('auth complete');
    // twitch.rig.log("userId:" + auth.userId);

    hideOverlay();

});

function saveUserId(auth) {
    twitchUserId = twitch.viewer.id;
    channelId = auth.channelId;

    // console.log("channelid:" + auth.channelId);
    // console.log("clientid:" + auth.clientId);
    // console.log("token:" + auth.token);
    // console.log("userId:" + auth.userId);

    // if autorized, remove overlay
    //$(".overlay").hide();
}

function hideOverlay() {
    if (twitch.viewer.isLinked == true)
        $(".overlay").hide();
}


$('#guessedNumberForm').submit(function(e) {
    e.preventDefault();
    var guessedNumber = parseInt($('#guessedNumber').val());

    const url = "http://localhost/twitch-related/guess-number/slime-side/objects/guess.php";

    if (guessedNumber) {
        $.get(url, {
                /* action: 'guess_number', */
                channelId: channelId,
                guess: guessedNumber,
                twitchUserId: twitchUserId
            })
            .done(function(result) {
                if (result.result == "won") {
                    $(".messages").prepend('<div class="alert alert-success">Correct, you won 45 points <br/> You now have ' + result.currentPoints.points + ' points.</div>');
                }
                if (result.result == "loss") {
                    $(".messages").prepend('<div class="alert alert-warning">You guessed the wrong number. Better luck next time.<br/> You now have ' + result.currentPoints.points + ' points.</div>');
                }
                if (result.result == "numberRange") {
                    $(".messages").prepend('<div class="alert alert-warning">Only enter numbers in range from 1 to 10</div>');
                }
                // console.log(result);
                // console.log(result.currentPoints.points);
            })
            .fail(function(data, status) {
                // console.log(data);
                // console.log(status);
                // console.log('query failed');
            })
            .always(function() { // after every form submission 
                setTimeout(function() {
                    $(".alert").last().remove();
                }, 5000);
                $("#guessedNumber").val(''); // resets input value
            });

    } else {
        $(".messages").prepend('<div class="alert alert-danger">Error: please enter number</div>');
    }
});