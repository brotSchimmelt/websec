<?php
session_start();

/*
* check if user exploited the comment field and showed the 'payload' as alert(),
* prompt() or confirm().
*/
if (isset($_POST['storedXSSMessage'])) {

    // check generously for 'evildomain/payload.js'
    $pos1 = stripos($_POST['storedXSSMessage'], "payload");
    $pos2 = stripos($_POST['storedXSSMessage'], "evil");
    $pos3 = stripos($_POST['storedXSSMessage'], "domain");

    if ($pos1 !== false && $pos2 !== false && $pos3 !== false) {

        // save that the XSS alert was used by user
        $_SESSION['storedXSSAlertShown'] = true;

        $msg = "The payload worked! You obtained 1 stolen session cookie. "
            . $_SESSION['myTest'];

        // echo is the preferred way to return a response to a $.post() request
        echo $msg;
    } else {
        // error code 1 means no valid attack detected
        // show the users original alert with a hint
        echo 1;
    }
}
