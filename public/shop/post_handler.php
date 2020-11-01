<?php
session_start(); // needs to be called first on every page

/*
* check if user exploited the comment field and showed the 'payload' as alert(),
* prompt() or confirm().
*/
if (isset($_POST['storedXSSMessage'])) {

    // check if cookie is already set
    if (isset($_COOKIE['XSS_STOLEN_SESSION'])) {

        // suppress alert() if user has already seen 'welcome back' modal
        if (
            isset($_SESSION['showStoredXSSModal'])
            && $_SESSION['showStoredXSSModal'] == 1
        ) {
            // echo is the preferred way to return a response to a $.post()
            // request
            echo 0;
        }
    } else {

        $haystack = (string)$_POST['storedXSSMessage'];

        // check generously for 'evildomain/cookie.php' + document.cookie
        $pos1 = stripos($haystack, "cookie");
        $pos2 = stripos($haystack, "evil");
        $pos3 = stripos($haystack, "domain");
        $pos4 = stripos($haystack, "document.cookie");
        $pos5 = stripos($haystack, "XSS_YOUR_SESSION");

        if (
            $pos1 !== false && $pos2 !== false && $pos3 !== false
            && ($pos4 !== false || $pos5 !== false)
        ) {

            $msg = "Your attack worked! You obtained 1 stolen session cookie "
                . "from an unsuspecting victim.\n\nXSS_STOLEN_SESSION="
                . $_SESSION['storedXSS'] . "\n\nYou can now set the cookie and "
                . "effectively steal the session of the victim by pressing "
                . "'OK' or come back later!";

            // return success message
            echo $msg;
        } else {
            // error code 1 means no valid attack detected
            echo 1;
        }
    }
}

// check if CSRF has been solved
if (isset($_POST['checkCSRF'])) {

    if (isset($_SESSION['csrfResult'])) {
        echo $_SESSION['csrfResult'];
        unset($_SESSION['csrfResult']);
    } else {
        echo -1;
    }
}
