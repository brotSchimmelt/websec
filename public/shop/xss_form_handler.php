<?php
session_start();

/*
* check if user exploited the comment field and showed the 'payload' as alert(),
* prompt() or confirm().
*/
if (isset($_POST['storedXSSMessage'])) {

    $haystack = (string)$_POST['storedXSSMessage'];

    // check generously for 'evildomain/payload.php'
    $pos1 = stripos($haystack, "payload");
    $pos2 = stripos($haystack, "evil");
    $pos3 = stripos($haystack, "domain");
    $pos4 = stripos($haystack, "document.cookie");

    if (($pos1 !== false && $pos2 !== false && $pos3 !== false) || $pos4 !== false) {

        // save that the XSS alert was used by user
        $_SESSION['storedXSSAlertShown'] = true;

        $msg = "Your attack worked! You obtained 1 stolen session cookie from "
            . "an unsuspecting victim. XSS_STOLEN_SESSION="
            . $_SESSION['storedXSS'];

        // echo is the preferred way to return a response to a $.post() request
        echo $msg;
    } else {
        // error code 1 means no valid attack detected
        // show the users original alert with a hint
        echo 1;
    }
}
