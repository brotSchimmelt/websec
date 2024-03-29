<?php
session_start(); // needs to be called first on every page

// load config files
require_once("$_SERVER[DOCUMENT_ROOT]/../config/config.php");
require_once(CONF_DB_LOGIN);

// load functions
require(FUNC_BASE);
require(FUNC_LOGIN);
require(FUNC_SHOP);
require(ERROR_HANDLING);

// load user messages
require(MESSAGES);

// check login status
if (!is_user_logged_in()) {
    // redirect to login page
    header("location: " . LOGIN_PAGE . "?login=false");
    exit();
}

// variables
$username = $_SESSION['userName'];
$num = get_number_of_cart_items();
$thisPage = basename(__FILE__);

// check if user read the instructions
if (isset($_POST['unlock-submit'])) {
    unlock_user($username);
}

// check if user is unlocked
if (!is_user_unlocked()) {
    $notUnlocked = true;
} else {
    $notUnlocked = false;
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

    <!-- Custom CSS to overwrite Bootstrap.css -->
    <link rel="stylesheet" href="/assets/css/shop.css">

    <!-- Link to favicon -->
    <link rel="shortcut icon" type="image/png" href="/assets/img/favicon.png">

    <title>WebSec Shop</title>
</head>

<body>
    <?php
    // load navbar
    require_once(HEADER_SHOP);

    // check if administrator has to change default password
    if ($_SESSION['pwdChangeReminder']) {
        echo $changeDefaultPwdReminder;
    }
    ?>
    <header id="mainHeader">
        <div class="dark-overlay">
            <div id="homeInner">
                <div class="container" id="headerContainer">
                    <div class="row">
                        <div class="col-lg-8">
                            <h1 class="display-4">Web Security Merch Shop</h1>
                            <div class="d-flex flex-row">
                                <div class="p-4 align-self-start">
                                    <svg width="2.5em" height="2.5em" viewBox="0 0 16 16" class="bi bi-cart3" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                                        <path fill-rule="evenodd" d="M0 1.5A.5.5 0 0 1 .5 1H2a.5.5 0 0 1 .485.379L2.89 3H14.5a.5.5 0 0 1 .49.598l-1 5a.5.5 0 0 1-.465.401l-9.397.472L4.415 11H13a.5.5 0 0 1 0 1H4a.5.5 0 0 1-.491-.408L2.01 3.607 1.61 2H.5a.5.5 0 0 1-.5-.5zM3.102 4l.84 4.479 9.144-.459L13.89 4H3.102zM5 12a2 2 0 1 0 0 4 2 2 0 0 0 0-4zm7 0a2 2 0 1 0 0 4 2 2 0 0 0 0-4zm-7 1a1 1 0 1 0 0 2 1 1 0 0 0 0-2zm7 0a1 1 0 1 0 0 2 1 1 0 0 0 0-2z" />
                                    </svg>
                                </div>
                                <div class="p-4 align-self-end">
                                    Find all the Web Security merchandise products you didn't know exist but desperately need!
                                </div>
                            </div>
                            <div class="d-none d-sm-block">
                                <div class="d-flex flex-row">
                                    <div class="p-4 align-self-start">
                                        <svg width="2.5em" height="2.5em" viewBox="0 0 16 16" class="bi bi-shield-check" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                                            <path fill-rule="evenodd" d="M5.443 1.991a60.17 60.17 0 0 0-2.725.802.454.454 0 0 0-.315.366C1.87 7.056 3.1 9.9 4.567 11.773c.736.94 1.533 1.636 2.197 2.093.333.228.626.394.857.5.116.053.21.089.282.11A.73.73 0 0 0 8 14.5c.007-.001.038-.005.097-.023.072-.022.166-.058.282-.111.23-.106.525-.272.857-.5a10.197 10.197 0 0 0 2.197-2.093C12.9 9.9 14.13 7.056 13.597 3.159a.454.454 0 0 0-.315-.366c-.626-.2-1.682-.526-2.725-.802C9.491 1.71 8.51 1.5 8 1.5c-.51 0-1.49.21-2.557.491zm-.256-.966C6.23.749 7.337.5 8 .5c.662 0 1.77.249 2.813.525a61.09 61.09 0 0 1 2.772.815c.528.168.926.623 1.003 1.184.573 4.197-.756 7.307-2.367 9.365a11.191 11.191 0 0 1-2.418 2.3 6.942 6.942 0 0 1-1.007.586c-.27.124-.558.225-.796.225s-.526-.101-.796-.225a6.908 6.908 0 0 1-1.007-.586 11.192 11.192 0 0 1-2.417-2.3C2.167 10.331.839 7.221 1.412 3.024A1.454 1.454 0 0 1 2.415 1.84a61.11 61.11 0 0 1 2.772-.815z" />
                                            <path fill-rule="evenodd" d="M10.854 6.146a.5.5 0 0 1 0 .708l-3 3a.5.5 0 0 1-.708 0l-1.5-1.5a.5.5 0 1 1 .708-.708L7.5 8.793l2.646-2.647a.5.5 0 0 1 .708 0z" />
                                        </svg>
                                    </div>
                                    <div class="p-4 align-self-end">
                                        Now with 25% less data breaches! You can totally trust us with your personal and financial data.
                                    </div>
                                </div>
                            </div>
                            <div class="d-flex flex-row">
                                <div class="p-4 align-self-start">
                                    <svg width="2.5em" height="2.5em" viewBox="0 0 16 16" class="bi bi-patch-plus" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                                        <path fill-rule="evenodd" d="M10.273 2.513l-.921-.944.715-.698.622.637.89-.011a2.89 2.89 0 0 1 2.924 2.924l-.01.89.636.622a2.89 2.89 0 0 1 0 4.134l-.637.622.011.89a2.89 2.89 0 0 1-2.924 2.924l-.89-.01-.622.636a2.89 2.89 0 0 1-4.134 0l-.622-.637-.89.011a2.89 2.89 0 0 1-2.924-2.924l.01-.89-.636-.622a2.89 2.89 0 0 1 0-4.134l.637-.622-.011-.89a2.89 2.89 0 0 1 2.924-2.924l.89.01.622-.636a2.89 2.89 0 0 1 4.134 0l-.715.698a1.89 1.89 0 0 0-2.704 0l-.92.944-1.32-.016a1.89 1.89 0 0 0-1.911 1.912l.016 1.318-.944.921a1.89 1.89 0 0 0 0 2.704l.944.92-.016 1.32a1.89 1.89 0 0 0 1.912 1.911l1.318-.016.921.944a1.89 1.89 0 0 0 2.704 0l.92-.944 1.32.016a1.89 1.89 0 0 0 1.911-1.912l-.016-1.318.944-.921a1.89 1.89 0 0 0 0-2.704l-.944-.92.016-1.32a1.89 1.89 0 0 0-1.912-1.911l-1.318.016z" />
                                        <path fill-rule="evenodd" d="M8 5.5a.5.5 0 0 1 .5.5v2a.5.5 0 0 1-.5.5H6a.5.5 0 0 1 0-1h1.5V6a.5.5 0 0 1 .5-.5z" />
                                        <path fill-rule="evenodd" d="M7.5 8a.5.5 0 0 1 .5-.5h2a.5.5 0 0 1 0 1H8.5V10a.5.5 0 0 1-1 0V8z" />
                                    </svg>
                                </div>
                                <div class="p-4 align-self-end">
                                    Upgrade your account to our premium subscription service to receive a 50% discount on all our prices and a daily newsletter why linux good windows bad.
                                </div>
                            </div>
                            <div class="text-center">
                                <a class="btn btn-outline-light" href="overview.php">Discover Our Products</a>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </header>

    <!-- Container 2 Product Text-->
    <section id="container2" class="text-muted py-5">
        <div class="container">
            <div class="row">
                <div class="col-md-6">
                    <img class="img-fluid mb-3 rounded-circle" src="../assets/img/iphone.jpg" alt="picture of clothes">
                </div>

                <div class="col-md-6">
                    <h3 class="green">Find Out More About Our Products</h3>
                    <p>Our outstanding products are always designed with the needs of our loyal customers in mind. What does a security affine person want the most in todays world of increasing online surveillance and privacy infringing mega-corporations? Merchandise!</p>
                    <div class="d-flex flex-row">
                        <div class="p-4 align-self-start">
                            <svg width="2em" height="2em" viewBox="0 0 16 16" class="bi bi-clipboard-data" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                                <path fill-rule="evenodd" d="M4 1.5H3a2 2 0 0 0-2 2V14a2 2 0 0 0 2 2h10a2 2 0 0 0 2-2V3.5a2 2 0 0 0-2-2h-1v1h1a1 1 0 0 1 1 1V14a1 1 0 0 1-1 1H3a1 1 0 0 1-1-1V3.5a1 1 0 0 1 1-1h1v-1z" />
                                <path fill-rule="evenodd" d="M9.5 1h-3a.5.5 0 0 0-.5.5v1a.5.5 0 0 0 .5.5h3a.5.5 0 0 0 .5-.5v-1a.5.5 0 0 0-.5-.5zm-3-1A1.5 1.5 0 0 0 5 1.5v1A1.5 1.5 0 0 0 6.5 4h3A1.5 1.5 0 0 0 11 2.5v-1A1.5 1.5 0 0 0 9.5 0h-3z" />
                                <path d="M4 11a1 1 0 1 1 2 0v1a1 1 0 1 1-2 0v-1zm6-4a1 1 0 1 1 2 0v5a1 1 0 1 1-2 0V7zM7 9a1 1 0 0 1 2 0v3a1 1 0 1 1-2 0V9z" />
                            </svg>
                        </div>
                        <div class="p-4 align-self-end">
                            With the help of Big Data and Machine Learning we designed our exquisite selection of products.
                        </div>
                    </div>
                    <div class="d-flex flex-row">
                        <div class="p-4 align-self-start">
                            <svg width="2em" height="2em" viewBox="0 0 16 16" class="bi bi-emoji-heart-eyes" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                                <path fill-rule="evenodd" d="M8 15A7 7 0 1 0 8 1a7 7 0 0 0 0 14zm0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16z" />
                                <path fill-rule="evenodd" d="M11.315 10.014a.5.5 0 0 1 .548.736A4.498 4.498 0 0 1 7.965 13a4.498 4.498 0 0 1-3.898-2.25.5.5 0 0 1 .548-.736h.005l.017.005.067.015.252.055c.215.046.515.108.857.169.693.124 1.522.242 2.152.242.63 0 1.46-.118 2.152-.242a26.58 26.58 0 0 0 1.109-.224l.067-.015.017-.004.005-.002zM4.756 4.566c.763-1.424 4.02-.12.952 3.434-4.496-1.596-2.35-4.298-.952-3.434zm6.488 0c1.398-.864 3.544 1.838-.952 3.434-3.067-3.554.19-4.858.952-3.434z" />
                            </svg>
                        </div>
                        <div class="p-4 align-self-end">
                            Our newest creation is the revolutionary banana slicer S. By incorporating user feedback in the new design, we were able to increase the success rate of the slicing process to 50%!
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Container 3 Friends Cover-->
    <section id="container3">
        <div class="dark-overlay-friends">
            <div class="container">
                <div class="row">
                    <div class="col text-center">
                        <div class="p-5">
                            <h1 class="display-4">
                                Find Your Friends
                            </h1>
                            <hr class="accent-white">
                            <p class="lead d-none d-sm-block">
                                Do you ever wanted to know what products your friends need? Well, now you can!
                            </p>
                            <a class="btn btn-outline-light" href="friends.php">Search For Your Friends</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Container 4 Friends Text-->
    <section id="container4" class="text-muted py-5">
        <div class="container">
            <div class="row">
                <div class="col-md-6">
                    <h3 class="green">See What Your Friends Want</h3>
                    <p>With our dynamic, AI-based market research techniques, we were able to increase our active user base to a total of <b>4</b>! So, there is a chance that one of your friends is already using our site.</p>
                    <div class="d-flex flex-row">
                        <div class="p-4 align-self-start">
                            <svg width="2em" height="2em" viewBox="0 0 16 16" class="bi bi-chevron-compact-right" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                                <path fill-rule="evenodd" d="M6.776 1.553a.5.5 0 0 1 .671.223l3 6a.5.5 0 0 1 0 .448l-3 6a.5.5 0 1 1-.894-.448L9.44 8 6.553 2.224a.5.5 0 0 1 .223-.671z" />
                            </svg>
                        </div>
                        <div class="p-4 align-self-end">
                            With our great, privacy compliant search feature you can see the private whish list of your friends!
                        </div>
                    </div>
                    <div class="d-flex flex-row">
                        <div class="p-4 align-self-start">
                            <svg width="2em" height="2em" viewBox="0 0 16 16" class="bi bi-chevron-compact-right" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                                <path fill-rule="evenodd" d="M6.776 1.553a.5.5 0 0 1 .671.223l3 6a.5.5 0 0 1 0 .448l-3 6a.5.5 0 1 1-.894-.448L9.44 8 6.553 2.224a.5.5 0 0 1 .223-.671z" />
                            </svg>
                        </div>
                        <div class="p-4 align-self-end">
                            You just have to enter their user names and we will query our secure user database to find their personal data.
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <img class="img-fluid mb-3 rounded-circle" src="../assets/img/friends.jpg" alt="friends">
                </div>
            </div>
        </div>
    </section>

    <!-- Container 5 Contact Cover-->
    <section id="container5">
        <div class="container">
            <div class="row">
                <div class="col text-center">
                    <div class="p-5">
                        <h1 class="display-4">
                            Any Questions?
                        </h1>
                        <hr class="accent-white">
                        <p class="lead">
                            Our support team is there for you and your questions. You can contact us 24/7, 365 days a year. We might just not answer.
                        </p>
                        <a class="btn btn-outline-light" href="contact.php">Contact Us</a>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Container 6 Contact Text-->
    <section id="container6" class="text-muted py-5">
        <div class="container">
            <div class="row">
                <div class="col-md-6">
                    <img class="img-fluid mb-3 rounded-circle" src="../assets/img/question.jpg" alt="contact">
                </div>
                <div class="col-md-6">
                    <h3 class="green">We Are Happy to Help</h3>
                    <p>We appreciate any feedback you might have for us and our products. The best way to contact us is via our contact form.</p>
                    <div class="d-flex flex-row">
                        <div class="p-4 align-self-start">
                            <svg width="2em" height="2em" viewBox="0 0 16 16" class="bi bi-chat-text" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                                <path fill-rule="evenodd" d="M2.678 11.894a1 1 0 0 1 .287.801 10.97 10.97 0 0 1-.398 2c1.395-.323 2.247-.697 2.634-.893a1 1 0 0 1 .71-.074A8.06 8.06 0 0 0 8 14c3.996 0 7-2.807 7-6 0-3.192-3.004-6-7-6S1 4.808 1 8c0 1.468.617 2.83 1.678 3.894zm-.493 3.905a21.682 21.682 0 0 1-.713.129c-.2.032-.352-.176-.273-.362a9.68 9.68 0 0 0 .244-.637l.003-.01c.248-.72.45-1.548.524-2.319C.743 11.37 0 9.76 0 8c0-3.866 3.582-7 8-7s8 3.134 8 7-3.582 7-8 7a9.06 9.06 0 0 1-2.347-.306c-.52.263-1.639.742-3.468 1.105z" />
                                <path fill-rule="evenodd" d="M4 5.5a.5.5 0 0 1 .5-.5h7a.5.5 0 0 1 0 1h-7a.5.5 0 0 1-.5-.5zM4 8a.5.5 0 0 1 .5-.5h7a.5.5 0 0 1 0 1h-7A.5.5 0 0 1 4 8zm0 2.5a.5.5 0 0 1 .5-.5h4a.5.5 0 0 1 0 1h-4a.5.5 0 0 1-.5-.5z" />
                            </svg>
                        </div>
                        <div class="p-4 align-self-end">
                            Our awesome new chat bot will try to answer all your questions!
                        </div>
                    </div>
                    <div class="d-flex flex-row">
                        <div class="p-4 align-self-start">
                            <svg width="2em" height="2em" viewBox="0 0 16 16" class="bi bi-x-octagon" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                                <path fill-rule="evenodd" d="M4.54.146A.5.5 0 0 1 4.893 0h6.214a.5.5 0 0 1 .353.146l4.394 4.394a.5.5 0 0 1 .146.353v6.214a.5.5 0 0 1-.146.353l-4.394 4.394a.5.5 0 0 1-.353.146H4.893a.5.5 0 0 1-.353-.146L.146 11.46A.5.5 0 0 1 0 11.107V4.893a.5.5 0 0 1 .146-.353L4.54.146zM5.1 1L1 5.1v5.8L5.1 15h5.8l4.1-4.1V5.1L10.9 1H5.1z" />
                                <path fill-rule="evenodd" d="M4.646 4.646a.5.5 0 0 1 .708 0L8 7.293l2.646-2.647a.5.5 0 0 1 .708.708L8.707 8l2.647 2.646a.5.5 0 0 1-.708.708L8 8.707l-2.646 2.647a.5.5 0 0 1-.708-.708L7.293 8 4.646 5.354a.5.5 0 0 1 0-.708z" />
                            </svg>
                        </div>
                        <div class="p-4 align-self-end">
                            The contact form is currently closed due to recent hacker activities.
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Greeting Popup -->
    <div class="modal fade" id="greeting" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="greetingLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-scrollable modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header wwu-green-background text-white shadow">
                    <h3 class="modal-title" id="greetingLabel">Instructions</h3>
                </div>
                <div class="modal-body">
                    <div class="mx-3">
                        <br>
                        <?php
                        // load instructions
                        get_challenge_instructions(['general', 'xss', 'sqli', 'csrf']);
                        ?>
                    </div>
                    <div class="text-center justify-content-center">
                        <br>
                        <form class="form-signin" action="<?= $thisPage ?>" method="post">
                            <div class="form-check">
                                <input type="checkbox" class="form-check-input" id="check" name="check" required>
                                <label class="form-check-label" for="check">I've read the instructions!</label>
                            </div>
                            <button type="submit" name="unlock-submit" id="unlock-btn" class="btn btn-wwu-cart mt-2">Let's Go!</button>
                        </form>
                        <br>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <?php
    // load shop footer
    require(FOOTER_SHOP);
    // load JavaScript
    require_once(JS_BOOTSTRAP); // default Bootstrap JavaScript
    require_once(JS_SHOP); // custom JavaScript
    if ($notUnlocked) {
        echo "<script>$('#greeting').modal('show')</script>";
    }

    if ($_SESSION['pwdChangeReminder']) {
        echo "<script>$('#pwd-change-reminder').modal('show')</script>";
    }
    ?>
</body>

</html>