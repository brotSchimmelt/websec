<?php

function display_registration_error($errorCode)
{
    if ($errorCode == "invalidNameAndMail") {
        $msg = "Your username and your mail address both do not satisfy the requirements. Please check again!";
        echo "<script type='text/javascript'>alert('$msg');</script>";
    } else if ($errorCode == "invalidUsername") {
        $msg = "Please use only letters and numbers and 2 to 64 characters!";
        echo "<script type='text/javascript'>alert('$msg');</script>";
    } else if ($errorCode == "invalidMail") {
        $msg = "Please use your @uni-muenster.de mail address!";
        echo "<script type='text/javascript'>alert('$msg');</script>";
    } else if ($errorCode == "passwordMismatch") {
        $msg = "Please re-enter your password!";
        echo "<script type='text/javascript'>alert('$msg');</script>";
    } else if ($errorCode == "sqlError") {
        $msg = "There was an error with the database. Please post this error code in Learnweb: 42";
        echo "<script type='text/javascript'>alert('$msg');</script>";
    } else if ($errorCode == "nameTaken") {
        $msg = "Sorry, that name is already taken!";
        echo "<script type='text/javascript'>alert('$msg');</script>";
    } else if ($errorCode == "invalidPassword") {
        $msg = "Your password must be at least 8 characters long!";
        echo "<script type='text/javascript'>alert('$msg');</script>";
    }
}
