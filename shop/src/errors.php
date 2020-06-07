<?php

function display_registration_error($errorCode)
{
    if ($errorCode == "invalidUsername") {
        $msg = "Please use only letters and numbers and 2 to 64 characters!";
        echo "<script type='text/javascript'>alert('$msg');</script>";
    } else if ($errorCode == "invalidMail") {
        $msg = "Please use your @uni-muenster.de mail address!";
        echo "<script type='text/javascript'>alert('$msg');</script>";
    }
}
