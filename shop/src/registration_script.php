<?php
// load DB connection
require("$_SERVER[DOCUMENT_ROOT]/../config/db_user_config.php");
// load extra functions
require("$_SERVER[DOCUMENT_ROOT]/../src/functions.php");

// paths
$registerPage = "registration.php";
$loginPage = "index.php";

// check if username AND mail are okay
if (!validate_username($_POST['username']) && !validate_mail($_POST['mail'])) {
    header("Location: " . $registerPage . "?error=invalidNameAndMail");
    exit();
}
// validate the username alone
else if (!validate_username($_POST['username'])) {
    $oldInput = "&mail=" . $_POST['mail'];
    header("Location: " . $registerPage . "?error=invalidUsername" . $oldInput);
    exit();
}
// validate the mail adress alone
else if (!validate_mail($_POST['mail'])) {
    $oldInput = "&username=" . $_POST['username'];
    header("Location: " . $registerPage . "?error=invalidMail" . $oldInput);
    exit();
}
// validate the password
else if (!validate_pwd($_POST['password'])) {
    $oldInput = "&username=" . $_POST['username'] . "&mail=" . $_POST['mail'];
    header("Location: " . $registerPage . "?error=invalidPassword" . $oldInput);
}
// check if the passwords match
else if ($_POST['password'] !== $_POST['confirmPassword']) {
    $oldInput = "&username=" . $_POST['username'] . "&mail=" . $_POST['mail'];
    header("Location: " . $registerPage . "?error=passwordMismatch" . $oldInput);
    exit();
} else {
    // check if username already exits in the db
    try {
        $duplicateQuery = $pdoLogin->prepare("SELECT 1 FROM users WHERE user_name = ?");
        $duplicateQuery->execute([$_POST['username']]);
    } catch (Exception $e) {
        header("Location: " . $registerPage . "?error=sqlError");
        exit();
    }
    $nameExists = $duplicateQuery->fetchColumn();
    if ($nameExists) {
        $oldInput = "&mail=" . $_POST['mail'];
        header("Location: " . $registerPage . "?error=nameTaken" . $oldInput);
        exit();
    }
    // add user to the db
    else {
        $pwdHash = hash_user_pwd($_POST['password']);

        try {
            $insertUser = "INSERT INTO users (user_name, user_mail, user_pwd_hash) VALUE (?, ?, ?)";
            $pdoLogin->prepare($insertUser)->execute([$_POST['username'], $_POST['mail'], $pwdHash]);
        } catch (Exception $e) {
            header("Location: " . $registerPage . "?error=sqlError");
            exit();
        }
        // redirect back to login page
        $loginPage = "index.php";
        header("location: " . $loginPage . "?signup=success");
        exit();
    }
}
