<?php
session_start(); // Needs to be called first on every page

// Load config files
require_once("$_SERVER[DOCUMENT_ROOT]/../config/config.php");

// Load custom libraries
require(FUNC_BASE);
require(FUNC_SHOP);

// Load error handling and user messages
require(ERROR_HANDLING);

// Check admin status
if (!is_user_logged_in()) {
    // Redirect to login page
    header("location: " . LOGIN_PAGE . "?login=false");
    exit();
}

// Load POST or GET variables and sanitize input BELOW this comment
if (isset($_POST['uName']) && isset($_POST['userPost'])) {
    $referrer = $_SERVER['HTTP_REFERER'];
    $pos1 = strpos($referrer, "product.php");
    $pos2 = strpos($referrer, "overview.php");
    if ($pos1 != false || $pos2 != false) {
        // request comes from one of the xss sites
        $userName = $_POST['uName'];
        $userPost = $_POST['userPost'];
        if ($userName == $_SESSION['userName']) {

            $sql = "SELECT username FROM crossposts WHERE username";






            $mysqli = new mysqli("db_websec", "websec", "S0s3zwo9z3hn", "websec");
            if (mysqli_connect_errno()) {
                printf("Connect failed: %s\n", mysqli_connect_error());
                exit();
            }
            $query = "SELECT username FROM crossposts WHERE username = '" . $userName . "';";
            if ($result = $mysqli->query($query)) {
                if ($result->num_rows < 1) {

                    $timestamp = date("Ymd-His");
                    echo ($timestamp);
                    #$mysqli = new mysqli("127.0.0.1", "websec", "S0s3zwo9z3hn", "websec"); if ($mysqli->connect_errno) {
                    #	echo "Failed to connect to MySQL: (" . $mysqli->connect_errno . ") " . $mysqli->connect_error;
                    #}
                    if (!($stmt = $mysqli->prepare("INSERT INTO crossposts (username,post,timestamp,referrer) VALUES (?,?,?,?)"))) {
                        echo "error (bkwm): (" . $mysqli->errno . ") " . $mysqli->error;
                    }
                    if (!$stmt->bind_param("ssss", $userName, $userPost, $timestamp, $referrer)) {
                        echo "error (yqwh): (" . $stmt->errno . ") " . $stmt->error;
                    }
                    if (!$stmt->execute()) {
                        echo "error (bhwn): (" . $stmt->errno . ") " . $stmt->error;
                    }
                    $stmt->close();
                    echo '<h4>Thank You!</h4>We have received your request and will come back to you very soon.<br>Very soon! Really!<br>One day..<br>or never.';
                } else {
                    echo 'you have already posted a request!';
                }
            } else {
                echo 'error (qlem)';
            }
            $mysqli->close();
        } else {
            // wrong user
            echo 'error: user mismatch';
        }
    } else {
        // referrer incorrect
        echo '<h4>Something went wrong!</h4>You tried to contact us but the form is disabled.<br>Sorry, you will have to find another way..<br>Do not manipulate the disabled form!';
    }
}

?>
<!doctype html>
<html lang="en">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="/assets/css/bootstrap.css">

    <!-- Custom CSS to overwrite bootstrap.css -->
    <link rel="stylesheet" href="/assets/css/shop.css">

    <title>Websec | Contact</title>
</head>

<body>

    <?php
    // Load navbar
    require(HEADER_SHOP);
    // Load error messages, user notifications etc.
    require(MESSAGES);
    ?>


    <!-- HTML Content BEGIN -->
    <h2>Contact Our Support Team</h2>
    We are here for you every day, twentyfour hours a day, 365 days a year!
    <h4>Contact Form</h4>
    Dear customer,<br>
    our contact form has been temporarily disabled.<br>We were experiencing heavy hacker attacks at our website and decided<br>to shut down our services for a few days/weeks/months.<br>
    In urgent cases please contact our support team.<br>
    Thanks!<br>
    <br>
    <form action="contact.php" method="post" id="reviewform">
        your name:
        <input type="text" name="username" value="<?= $_SESSION['userName'] ?>" disabled><br>
        <input type="hidden" name="uName" value="<?= $_SESSION['userName'] ?>">
        your message for us:
        <input type="text" name="userPost" size="30" disabled><br><br>
        <input type="submit" value="Submit" disabled>
    </form>
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