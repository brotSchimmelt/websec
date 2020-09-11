<?php

// get error or success GET variables
function get_message()
{
    // Check if success is set
    if (isset($_GET['success']) && !empty($_GET['success'])) {

        $success = filter_input(INPUT_GET, 'success', FILTER_SANITIZE_STRING);
        return get_success_msg($success);

        // Check if error is set
    } else if (isset($_GET['error']) && !empty($_GET['error'])) {

        $error = filter_input(INPUT_GET, 'error', FILTER_SANITIZE_STRING);
        $errorCode = 0; // Default error code: (no code given)

        // Check if the error comes with an optional error code
        if (isset($_GET['code']) && !empty($_GET['code'])) {
            $errorCode = filter_input(
                INPUT_GET,
                'code',
                FILTER_SANITIZE_NUMBER_INT
            );
        }

        return get_error_msg($error, $errorCode);
    } else {

        // Return empty string if no useful GET message is found
        return "";
    }
}

// return success message to given success type
function get_success_msg($success)
{
    $msgType = "success";

    switch ($success) {
        case "logout":
            $msg = "You were successfully <b>logged out</b>!";
            return format_msg($msg, $msgType);
            break;
        case "signup":
            $msg = "You successfully signed up. Try the <b>login</b> now!";
            return format_msg($msg, $msgType);
            break;
        case "resetPwd":
            $msg = "Your password was successfully <b>reset</b>!";
            return format_msg($msg, $msgType);
            break;
        case "requestProcessed":
            $msg = "<b>Success! </b>If your e-mail address is linked to an ";
            $msg .= "active account, ";
            $msg .= "you will receive an e-mail with further instructions on ";
            $msg .= "how to reset your password.";
            return format_msg($msg, $msgType);
            break;
        case "pwdChanged":
            $msg = "Your password was successfully <b>changed</b>!";
            return format_msg($msg, $msgType);
            break;
        default:
            $msg = "Your operation was successful!";
            return format_msg($msg, $msgType);
    }
}

// return error message to given error type
function get_error_msg($error, $errorCode)
{
    $msgType = "error";

    switch ($error) {
        case "sqlError":
            $msg = "Oh no! There was an error in the database. ";
            $msg .= "Please report this error in the Learnweb forum. ";
            $msg .= "Include this error code: <b>";
            $msg .= $errorCode . "</b>";
            return format_msg($msg, $msgType);
            break;
        case "wrongCredentials":
            $msg = "<b>Wrong credentials</b>! Please try again.";
            return format_msg($msg, $msgType);
            break;
        case "internalError":
            $msg = "Oh no! There was an internal error in the backend. ";
            $msg .= "Please report this error in the Learnweb forum.";
            return format_msg($msg, $msgType);
            break;
        case "invalidNameAndMail":
            $msg = "It seems like your user name <b>and</b> your e-mail ";
            $msg .= "address do not fulfill the requirements. Please choose a ";
            $msg .= "different user name and use your WWU e-mail account.";
            return format_msg($msg, $msgType);
            break;
        case "invalidUsername":
            $msg = "It seems like your user name does not fulfill ";
            $msg .= "the requirements. Please use only letters and numbers ";
            $msg .= "and 2 to 64 characters.";
            return format_msg($msg, $msgType);
            break;
        case "invalidMailFormat":
            $msg = "It seems like you are not using a valid e-mail address. ";
            $msg .= "Please try again with your WWU e-mail address!";
            return format_msg($msg, $msgType);
            break;
        case "invalidMail":
            $msg = "It seems like you are not using your WWU e-mail account. ";
            $msg .= "This is necessary for the grading.";
            return format_msg($msg, $msgType);
            break;
        case "invalidPassword":
            $msg = "Your password does not fulfill the requirements. ";
            $msg .= "Please use at least 8 characters!";
            return format_msg($msg, $msgType);
            break;
        case "passwordMismatch":
            $msg = "Your password does not match! Please retype it.";
            return format_msg($msg, $msgType);
            break;
        case "nameError":
            $msg = "Please use a different user name!";
            return format_msg($msg, $msgType);
            break;
        case "mailTaken":
            $msg = "This e-mail address is already taken. If you forgot ";
            $msg .= "your password, ";
            $msg .= "you can reset it ";
            $msg .= '<a href="password_reset.php">here</a>.';
            return format_msg($msg, $msgType);
            break;
        case "invalidToken":
            $msg = "Sorry, it seems like your reset link is not working. ";
            $msg .= "Please request a ";
            $msg .= '<a href="password_reset.php">new link here</a>. ';
            $msg .= "If the error persists, contact your lecturer.";
            return format_msg($msg, $msgType);
            break;
        case "missingToken":
            $msg = "Sorry, it seems like your reset link does not contain ";
            $msg .= "the necessary tokens. ";
            $msg .= "Please try the link from the mail again. ";
            $msg .= "If the error persists, request a ";
            $msg .= '<a href="password_reset.php">new link here</a> ';
            $msg .= "or contact the lecturer.";
            return format_msg($msg, $msgType);
            break;
        default:
            $msg = "An unknown <b>error</b> occurred. Please report this ";
            $msg .= "in the Learnweb forum.";
            return format_msg($msg, $msgType);
    }
}

// format user message
function format_msg($msgString, $msgType)
{

    $format = '<div class="alert alert-%s shadow" role="alert">%s</div>';

    switch ($msgType) {
        case "success":
            return sprintf($format, "success", $msgString);
            break;
        case "error":
            return sprintf($format, "danger", $msgString);
            break;
        default:
            return sprintf($format, "info", $msgString);
    }
}

// display and format exception messages
function display_exception_msg($exception, $errorCode = null, $note = null)
{
    // html for the error page
    include(INCL . "error_page.php");

    // default message
    if (is_null($exception) || !($exception instanceof Exception)) {
        $msg = "An unexpected error occurred.";
        echo "<strong>Error Message</strong>: " . $msg . "<br><br>";
    }
    // display exception information
    if ($exception instanceof Exception) {
        echo "<strong>Error Message</strong>: "
            . $exception->getMessage() . "<br>";
        echo "<strong>File</strong>: "
            . $exception->getFile() . "<br>";
        echo "<strong>Line</strong>: "
            . $exception->getLine() . "<br>";
        echo "<strong>Trace</strong>: "
            . $exception->getTraceAsString() . "<br><br>";
    }

    // additional information about the error
    if (is_null($note)) {
        $note = 'If this error persists, please post it to the '
            . '<a href="https://www.uni-muenster.de/LearnWeb/learnweb2/" '
            . 'target="_blank">Learnweb</a> forum.';
    }
    echo "<strong>Note</strong>: " . $note . "<br><br>";

    // custom error code
    if (!is_null($errorCode)) {
        echo "<strong>Error Code</strong>: " . $errorCode . "<br><br>";
    }

    // link to main.php
    echo '<br><br><hr><a href="../shop/main.php">Back to the Shop</a>';

    // close off error page
    echo "</div></body></html>";
}


function display_warning_msg($msg)
{
    // html for the error page
    include(INCL . "error_page.php");

    // start container
    echo '<div class="container text-center">';

    // display warning text
    echo "<strong>Warning</strong>: " . $msg . "<br><br>";
    echo 'If this warning persists, please post it to the '
        . '<a href="https://www.uni-muenster.de/LearnWeb/learnweb2/" '
        . 'target="_blank">Learnweb</a> forum.<br><br>';

    // closer container
    echo "</div>";

    // link to main.php
    echo '<br><br><hr><a href="../shop/main.php">Back to the Shop</a>';

    // close off error page
    echo "</div></body></html>";
}
