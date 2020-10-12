<?php
session_start(); // Needs to be called first on every page

// Load config files
require_once("$_SERVER[DOCUMENT_ROOT]/../config/config.php");

// Load custom libraries
require(FUNC_BASE);
require(FUNC_SHOP);

// Load error handling and user messages
require(ERROR_HANDLING);

// Check login status
if (!is_user_logged_in()) {
    // Redirect to login page
    header("location: " . LOGIN_PAGE . "?login=false");
    exit();
}

// check if user is unlocked
if (!is_user_unlocked()) {
    header("location: " . MAIN_PAGE);
    exit();
}

// Load POST or GET variables and sanitize input BELOW this comment

?>
<!doctype html>
<html lang="en">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="/assets/css/vendor/bootstrap.css">

    <!-- Custom CSS to overwrite bootstrap.css -->
    <link rel="stylesheet" href="/assets/css/shop.css">

    <!-- Link to favicon -->
    <link rel="shortcut icon" type="image/png" href="/assets/img/favicon.png">

    <title>Websec | Help</title>
</head>

<body>

    <?php
    // Load navbar
    require(HEADER_SHOP);
    // Load error messages, user notifications etc.
    require(MESSAGES);
    ?>

    <!-- HTML Content BEGIN -->
    <div class="page-container">
        <h1 class="display-4">User Instructions</h1>
        <hr>
        <br>

        <!-- Vertical List Group-->
        <div id="help-container">
            <div class="list-group help-list-group" id="list-tab" role="tablist">
                <a class="list-group-item list-group-item-action active" id="list-general-list" data-toggle="list" href="#list-general" role="tab" aria-controls="general">Genral Instructions</a>
                <a class="list-group-item list-group-item-action" id="list-xss-list" data-toggle="list" href="#list-xss" role="tab" aria-controls="xss">Cross-Site Scripting</a>
                <a class="list-group-item list-group-item-action" id="list-sqli-list" data-toggle="list" href="#list-sqli" role="tab" aria-controls="sqli">SQL Injections</a>
                <a class="list-group-item list-group-item-action" id="list-csrf-list" data-toggle="list" href="#list-csrf" role="tab" aria-controls="csrf">Contact Form Challenge</a>
            </div>
            <div class="tab-content help-text-list-group" id="nav-tabContent">
                <!-- General Instructions -->
                <div class="tab-pane fade show active" id="list-general" role="tabpanel" aria-labelledby="list-general-list">
                    <h4 class="text-wwu-green">General Rules</h4>
                    <p>
                        Please read the following instructions <em>carefully</em>!
                        <br>
                        This website is a learning tool for the corresponding course Web Security at the University of Münster.
                        This website yields security vulnerabilities that can be abused.
                        These vulnerabilities are intended for learning purpose and you are not allowed to exploit these in any other way!
                        <br>
                        Any violation of only one of these rules will ban you from this course.
                        Furthermore, in case of violation legal measures will be taken!
                        <br>
                        You are bound the lecturer's and tutor's instructions!
                    </p>
                    <p>Resetting: You can always reset every challenge. This will delete all your actions of the corresponding challenge and withdraw your achievements!</p>
                    <p>External tools: All challenges can (and must) be solved without the use of external tools! We keep track of how you solve the challenges and using any software, e.g., for automation, will make you immediately fail! You are here to learn about Web hacking and not about how to run a specific toolchain.</p>
                    <p>Browser Security: Most modern browsers have built-in security mechanisms to prevent attacks you need to perform here. Use an insecure browser, e.g., Microsoft Edge or Internet Explorer, for completing the challenges.</p>
                    <br>
                </div>
                <!-- XSS -->
                <div class="tab-pane fade" id="list-xss" role="tabpanel" aria-labelledby="list-xss-list">
                    <h4 class="text-wwu-green">Cross-Site Scripting</h4>
                    <p>
                        This website yields security vulnerabilities that can be abused for XSS.
                        You are not allowed to exploit these vulnerabilities in any other way than intended for your excercises.
                        <br>
                        There are two XSS challenges. The first one is a reflective XSS and simulates a search field.
                        The second challenge simulates a product review page.<br>
                        None of these pages yield real functionalities and are just simulations.
                    </p>
                    <p>
                        Task: Reflective XSS<br>
                        You can abuse the search field to read out a user's session ID that is stored in a cookie.<br>
                        To do this you will have to create a JavaScript code snippet that displays the document's cookie.<br>
                        Note the desired session ID. You will need it.
                    </p>
                    <p>
                        Task: Stored XSS<br>
                        The product reviews are stored in a database. Your task is to create a javascript code that will pop up a window displaying the session ID you obtained in the reflective XSS challenge.<br>
                    </p>
                </div>
                <!-- SQLi -->
                <div class="tab-pane fade" id="list-sqli" role="tabpanel" aria-labelledby="list-sqli-list">
                    <h4 class="text-wwu-green">SQL Injections</h4>
                    <p>
                        For SQLi challenges you will have a personal database.
                        You are not allowed to use automatic scripts on this database.
                        You must not take any actions to increase the database size more than necessary!
                        You have to keep the database size as small as possible.
                        We may delete or reset your database any time and it will be reset automatically if it grows too big!<br>
                        The SQLi challenges are a simulation of a user database.
                    </p>
                    <p>
                        Task: Inject Account<br>
                        The database yields a table named <em>users</em> containing all data of registered website users. Sadly, you do not know anything about the table's structure or data.<br>
                        However, your goal is to create a user account to this website. This account should have admin permissions.<br>
                        Good luck!
                    </p>
                </div>
                <!-- CSRF -->
                <div class="tab-pane fade" id="list-csrf" role="tabpanel" aria-labelledby="list-csrf-list">
                    <h4 class="text-wwu-green">Contact Form Challenge</h4>
                    <p>
                        This website has a (fake) contact form that lets you contact the support team.<br>
                        Too bad that due to recent hacker activity this form has been disabled and you cannot make any request.
                    </p>
                    <p>
                        Task: Post a Support Request<br>
                        Find a way to submit a support request. Your request message needs to be "pwned". That will show them!<br>
                        If you successfully posted your attack, you will see a "Thank you!" message.
                    </p>
                </div>

            </div>
        </div>
    </div>
    <!-- HTML Content END -->

    <?php
    // Load shop footer
    require(FOOTER_SHOP);
    // Load JavaScript
    require_once(JS_BOOTSTRAP); // Default Bootstrap JavaScript
    require_once(JS_SHOP); // Custom JavaScript
    ?>
</body>

</html>