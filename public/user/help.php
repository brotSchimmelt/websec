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

// initialize links to separate help sections
$general = "";
$xss = "";
$sqli = "";
$csrf = "";

// Load POST or GET variables and sanitize input BELOW this comment
if (isset($_GET['help'])) {

    $section = filter_input(INPUT_GET, 'help', FILTER_SANITIZE_STRING);

    // check for sections
    $xss = ($section == "xss") ? "active" : "";
    $sqli = ($section == "sqli") ? "active" : "";
    $csrf = ($section == "csrf") ? "active" : "";
}

// set default to list-group
if (empty($xss) && empty($sqli) && empty($csrf)) {
    $general = "active";
}
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
            <div class="list-group help-list-group mr-3" id="list-tab" role="tablist">
                <a class="list-group-item list-group-item-action <?= $general ?>" id="list-general-list" data-toggle="list" href="#list-general" role="tab" aria-controls="general">Genral Instructions</a>
                <a class="list-group-item list-group-item-action <?= $xss ?>" id="list-xss-list" data-toggle="list" href="#list-xss" role="tab" aria-controls="xss">Cross-Site Scripting</a>
                <a class="list-group-item list-group-item-action <?= $sqli ?>" id="list-sqli-list" data-toggle="list" href="#list-sqli" role="tab" aria-controls="sqli">SQL Injections</a>
                <a class="list-group-item list-group-item-action <?= $csrf ?>" id="list-csrf-list" data-toggle="list" href="#list-csrf" role="tab" aria-controls="csrf">Contact Form Challenge</a>
            </div>
            <div class="tab-content help-text-list-group" id="nav-tabContent">
                <!-- General Instructions -->
                <div class="tab-pane fade show <?= $general ?>" id="list-general" role="tabpanel" aria-labelledby="list-general-list">
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
                        You are bound by the lecturer's and tutor's instructions!
                    </p>
                    <p>
                        Resetting: You can always reset every challenge. This will delete all your actions of the corresponding challenge and withdraw your achievements!
                        This can be done by accessing the challenge settings in the account menu.
                    </p>
                    <p>External tools: All challenges can (and must) be solved without the use of external tools! We keep track of how you solve the challenges and using any software, e.g., for automation, will make you immediately fail! You are here to learn about web hacking and not about how to run a specific toolchain.</p>
                    <p><b>(TODO: add versions and/or OS information)</b>Browser Support: This website was tested with Google Chrome, Firefox and Safari. If you think one or more challenges are not solvable with your browser, try an insecure one like Microsoft Edge oder Microsoft Internet Explorer.</p>
                    <br>
                </div>
                <!-- XSS -->
                <div class="tab-pane fade show <?= $xss ?>" id="list-xss" role="tabpanel" aria-labelledby="list-xss-list">
                    <h4 class="text-wwu-green">Cross-Site Scripting (XSS)</h4>
                    <p>
                        This website yields security vulnerabilities that can be abused for XSS.
                        You are not allowed to exploit these vulnerabilities in any other way than intended for your exercises.
                        <br>
                        There are two XSS challenges. The first one is a reflective XSS and simulates a search field.
                        The second challenge simulates a product page with a comment field.<br>
                    </p>
                    <p>
                        <b>Task: Reflective XSS</b><br>
                        You can abuse the search field to read out a user's session ID that is stored in a cookie.<br>
                        To do this you will have to create a JavaScript code snippet that displays the document's cookie.<br>
                        Note or copy the obtained session ID. The site will detect if you found the session ID and will either show you a popup where you can enter the session ID or display a button beneath the search results to trigger said popup manually.
                        This depends on the way you obtained the session ID.
                    </p>
                    <p>
                        <b>Task: Stored XSS</b><br>
                        The product reviews are stored in a database. Your task is to create a JavaScript code snippet that simulates a cookie stealing attack.<br>
                        Luckily, you are a very well prepared attacker and you have already created a PHP page <em>cookie.php</em> in the root directory of your webserver <em>evil.domain</em>.
                        You have planed to obtain the session ID cookies for every visitor of the product review page by passing them as a GET variable to your PHP page. As a reminder, a GET variable is simply appended to the end of an URL with a ? followed by its name and its value (e.g. example.com?name=value).
                        To make things easier, you only have to show a JavaScript popup to the visitors with the link to your PHP page followed by their session ID as a GET variable. As soon as someone visits the site you will receive a popup with their session ID and an option to steal their session. This will probably happen rather quickly since this is a VERY popular site.
                        If you have successfully stolen the session of your victim, you should manually manipulate his/her shopping cart by adding a Banana Slicer. Everyone should have one these days!
                    </p>
                </div>
                <!-- SQLi -->
                <div class="tab-pane fade show <?= $sqli ?>" id="list-sqli" role="tabpanel" aria-labelledby="list-sqli-list">
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
                        <b>Task: Inject Account</b><br>
                        The database yields a table named <em>users</em> containing all data of registered website users. Sadly, you do not know anything about the table's structure or data.<br>
                        However, your goal is to update your user status to <em>premium</em>.<br>
                        Good luck!
                    </p>
                </div>
                <!-- CSRF -->
                <div class="tab-pane fade show <?= $csrf ?>" id="list-csrf" role="tabpanel" aria-labelledby="list-csrf-list">
                    <h4 class="text-wwu-green">Contact Form Challenge</h4>
                    <p>
                        This website has a (fake) contact form that lets you contact the support team.<br>
                        Too bad that due to recent hacker activity this form has been disabled and you cannot make any request.
                    </p>
                    <p>
                        <b>Task: Post a Support Request</b><br>
                        Find a way to submit a support request for the user <em>elliot</em>. Your request message needs to be "pwned". That will show them!<br>
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