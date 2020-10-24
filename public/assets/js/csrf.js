

var delay = 1500;
setTimeout(function () {
    var requestCSRF;
    requestCSRF = $.post("post_handler.php", {
        checkCSRF: 1
    });
    requestCSRF.done(function (response) {

        if (response == 0) {
            // success
            $('#challenge-success-csrf').modal('show');
        } else if (response == 1) {
            // wrong message; still passed
            $('#challenge-success-csrf-pwned').modal('show');
        } else if (response == 2) {
            // error: wrong user
            $('#challenge-info-csrf-user-mismatch').modal('show');
        } else if (response == 3) {
            // error: already post in the database
            $('#challenge-info-csrf-already-posted').modal('show');
        } else if (response == 4) {
            // wrong referrer; still passed
            $('#challenge-success-csrf-referrer').modal('show');
        }
    });
}, delay);