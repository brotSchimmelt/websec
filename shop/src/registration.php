<?php
require("../config/db_login_config.php"); // load DB credentials
require("functions.php"); // load extra functions

// check if the connection works
$db_login_conn = mysqli_connect(DB_HOST, DB_USER, DB_PASS, DB_NAME);
if ($db_login_conn->connect_error) {
    echo "Sorry, a connection to the database could not be established.";
} else {

    // check if user came from the register page
    if (isset($_POST['register-submit'])) { // name of the submit btn 

        $usr_input = array(
            "username" => $_POST['inputName'],
            "mail" => $_POST['inputMail'],
            "pwd" => $_POST['inputPassword'],
            "pwd_confirm" => $_POST['confirmPassword']
        );

        // check if one value from the user input is empty
        if (check_for_empty_values($usr_input)) {
            // send user back to register page
            header("location: ../register.php?error=emptyfields&inputName=" . $usr_input['username'] . "inputMail=" . $usr_input['mail']);
            exit();
        } else if() {
            
        }








        // end
    }
}
