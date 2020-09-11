<?php

if (isset($_GET['error']) && !empty($_GET['error'])) {

    $error = (string)$_GET['error'];

    $headline = $error . " Error";

    switch ($error) {
        case "400":
            $caption = "Bad Request";
            $msg = "The server cannot or will not process the request due to "
                . "something that is perceived to be a client error.";
            $link = "https://tools.ietf.org/html/rfc7231#section-6.5.1";
            break;
        case "401":
            $caption = "Unauthorized";
            $msg = "The request has not been applied because it lacks valid "
                . "authentication credentials for the target resource.";
            break;
            $link = "https://tools.ietf.org/html/rfc7235#section-3.1";
        case "403":
            $caption = "Forbidden";
            $msg = "The server understood the request but refuses to "
                . "authorize it.";
            $link = "https://tools.ietf.org/html/rfc7231#section-6.5.3";
            break;
        case "404":
            $caption = "Page Not Found";
            $msg = "The page you were looking for does not exist on this server.";
            $link = "https://tools.ietf.org/html/rfc7231#section-6.5.4";
            break;
        case "500":
            $caption = "Internal Server Error";
            $msg = "The server encountered an unexpected condition that "
                . "prevented it from fulfilling the request.";
            $link = "https://tools.ietf.org/html/rfc7231#section-6.6.1";
            break;
        default:
            $title = "Error Page";
            $headline = "Undefined Error";
            $caption = "An unexpected error occurred.";
            $msg = "";
            $link = "https://en.wikipedia.org/wiki/List_of_HTTP_status_codes";
    }
    // Page Title
    $title = $error . " - " . $caption;
} else {
    // default output
    $title = "Error Page";
    $headline = "Undefined Error";
    $caption = "An unexpected error occurred.";
    $msg = "";
    $link = "https://en.wikipedia.org/wiki/List_of_HTTP_status_codes";
}

?>
<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <title><?= $title ?></title>

    <!-- Bootstrap core CSS -->
    <link href="../assets/css/bootstrap.css" rel="stylesheet">

    <!-- Custom styles for this template -->
    <link href="../assets/css/doc.css" rel="stylesheet">

    <!-- Link to favicon -->
    <link rel="shortcut icon" type="image/png" href="/assets/img/favicon.png">
</head>

<body>
    <!-- Headline -->
    <br><br><br>
    <div class="doc-header px-3 py-3 pt-md-5 pb-md-4 mx-auto text-center">
        <h1 class="display-3"><?= $headline ?></h1>
        <p class="lead"><?= $caption ?></p>
    </div>

    <br><br>

    <!-- Content Container -->
    <div class="container text-center">
        <?= $msg ?>
        <br><br>
        <a href="<?= $link ?>" target="_blank">More Information</a>
    </div>
</body>

</html>