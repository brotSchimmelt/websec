<?php
session_start();

/*
* check if user exploited the comment field and showed the 'payload' as alert(),
* prompt() or confirm().
*/
if (isset($_POST['storedXSSMessage'])) {

    // check if cookie is already set
    if (isset($_COOKIE['XSS_STOLEN_SESSION'])) {

        // suppress alert() if user has already seen 'welcome back' modal
        if ($_SESSION['showStoredXSSModal'] == 1) {
            // echo is the preferred way to return a response to a $.post()
            // request
            echo 0;
        }
    } else {

        $haystack = (string)$_POST['storedXSSMessage'];

        // check generously for 'evildomain/payload.php' + document.cookie
        $pos1 = stripos($haystack, "payload");
        $pos2 = stripos($haystack, "evil");
        $pos3 = stripos($haystack, "domain");
        $pos4 = stripos($haystack, "document.cookie");

        if (
            $pos1 !== false && $pos2 !== false && $pos3 !== false
            || $pos4 !== false
        ) {

            $msg = "Your attack worked! You obtained 1 stolen session cookie "
                . "from an unsuspecting victim.\n\nXSS_STOLEN_SESSION="
                . $_SESSION['storedXSS'] . "\n\n You can now set the cookie by "
                . "pressing 'OK' or come back later!";

            echo $msg;
        } else {
            // error code 1 means no valid attack detected
            echo 1;
        }
    }
}
