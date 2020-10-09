<?php
session_start(); // Needs to be called first on every page

// Load config files
require_once("$_SERVER[DOCUMENT_ROOT]/../config/config.php");
require_once(CONF_DB_LOGIN);

// Load custom libraries
require(FUNC_BASE);
require(FUNC_LOGIN);
require(FUNC_SHOP);

// Load error handling and user messages
require(ERROR_HANDLING);
require(MESSAGES);

// Check login status
if (!is_user_logged_in()) {
    // Redirect to login page
    header("location: " . LOGIN_PAGE . "?login=false");
    exit();
}

// Load POST or GET variables and sanitize input BELOW this comment
$username = $_SESSION['userName'];
$num = get_number_of_cart_items();
$thisPage = basename(__FILE__);

// check if user read the instructions
if (isset($_POST['unlock-submit'])) {
    unlock_user($username);
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
    // Load navbar
    require_once(HEADER_SHOP);
    // // Load error messages, user notifications etc.
    // require(MESSAGES);

    // check if user is unlocked
    if (!is_user_unlocked()) {
        echo $modalGreeting;
        $unlocked = true;
    } else {
        $unlocked = false;
    }

    // check if administrator has to change default password
    if ($_SESSION['pwdChangeReminder']) {
        echo $changeDefaultPwdReminder;
    }
    ?>
    <header id="main-header">
        <div class="dark-overlay">
            <div id="home-inner">
                <div class="container" id="header-container">
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
                            <div class="d-flex flex-row">
                                <div class="p-4 align-self-start">
                                    <svg width="2.5em" height="2.5em" viewBox="0 0 16 16" class="bi bi-patch-plus" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                                        <path fill-rule="evenodd" d="M10.273 2.513l-.921-.944.715-.698.622.637.89-.011a2.89 2.89 0 0 1 2.924 2.924l-.01.89.636.622a2.89 2.89 0 0 1 0 4.134l-.637.622.011.89a2.89 2.89 0 0 1-2.924 2.924l-.89-.01-.622.636a2.89 2.89 0 0 1-4.134 0l-.622-.637-.89.011a2.89 2.89 0 0 1-2.924-2.924l.01-.89-.636-.622a2.89 2.89 0 0 1 0-4.134l.637-.622-.011-.89a2.89 2.89 0 0 1 2.924-2.924l.89.01.622-.636a2.89 2.89 0 0 1 4.134 0l-.715.698a1.89 1.89 0 0 0-2.704 0l-.92.944-1.32-.016a1.89 1.89 0 0 0-1.911 1.912l.016 1.318-.944.921a1.89 1.89 0 0 0 0 2.704l.944.92-.016 1.32a1.89 1.89 0 0 0 1.912 1.911l1.318-.016.921.944a1.89 1.89 0 0 0 2.704 0l.92-.944 1.32.016a1.89 1.89 0 0 0 1.911-1.912l-.016-1.318.944-.921a1.89 1.89 0 0 0 0-2.704l-.944-.92.016-1.32a1.89 1.89 0 0 0-1.912-1.911l-1.318.016z" />
                                        <path fill-rule="evenodd" d="M8 5.5a.5.5 0 0 1 .5.5v2a.5.5 0 0 1-.5.5H6a.5.5 0 0 1 0-1h1.5V6a.5.5 0 0 1 .5-.5z" />
                                        <path fill-rule="evenodd" d="M7.5 8a.5.5 0 0 1 .5-.5h2a.5.5 0 0 1 0 1H8.5V10a.5.5 0 0 1-1 0V8z" />
                                    </svg>
                                </div>
                                <div class="p-4 align-self-end">
                                    Upgrade your account to our premium subscription service to receive a 5% discount on all our prices and a daily newsletter why linux good windows bad.
                                </div>
                            </div>
                            <div class="text-center">
                                <a class="btn btn-outline-light" href="overview.php">Discover our Products</a>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </header>


    <!-- Container 1 -->
    <!-- <section id="container-1">
        <div class="container">
            <div class="row">
                <div class="col text-center">
                    <div class="p-5">
                        <h1 class="display-4">
                            Products
                        </h1>
                        <hr class="accent-green">
                        <p class="lead">Lorem ipsum dolor sit amet consectetur, adipisicing elit. Dicta maxime dignissimos voluptatem iste totam assumenda cumque eius architecto temporibus molestias.</p>
                        <a class="btn btn-login" href="product.php">See our Products</a>
                    </div>
                </div>
            </div>
        </div>
    </section> -->

    <!-- Container 2 Product Text-->
    <section id="container-2" class="text-muted py-5">
        <div class="container">
            <div class="row">
                <div class="col-md-6">
                    <img class="img-fluid mb-3 rounded-circle" src="../assets/img/iphone.jpg" alt="picture of clothes">
                </div>

                <div class="col-md-6">
                    <h3 class="green">Headline about the products</h3>
                    <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Voluptatum impedit tempora cumque non corrupti fuga quibusdam numquam laudantium similique natus consequuntur consectetur quo qui officia in, modi dolores expedita saepe?</p>
                    <div class="d-flex flex-row">
                        <div class="p-4 align-self-start">
                            <svg width="2em" height="2em" viewBox="0 0 16 16" class="bi bi-chevron-compact-right" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                                <path fill-rule="evenodd" d="M6.776 1.553a.5.5 0 0 1 .671.223l3 6a.5.5 0 0 1 0 .448l-3 6a.5.5 0 1 1-.894-.448L9.44 8 6.553 2.224a.5.5 0 0 1 .223-.671z" />
                            </svg>
                        </div>
                        <div class="p-4 align-self-end">
                            Lorem ipsum dolor sit amet, consectetur adipisicing elit. Ipsum unde consequatur fuga fugit tempore laborum.
                        </div>
                    </div>
                    <div class="d-flex flex-row">
                        <div class="p-4 align-self-start">
                            <svg width="2em" height="2em" viewBox="0 0 16 16" class="bi bi-chevron-compact-right" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                                <path fill-rule="evenodd" d="M6.776 1.553a.5.5 0 0 1 .671.223l3 6a.5.5 0 0 1 0 .448l-3 6a.5.5 0 1 1-.894-.448L9.44 8 6.553 2.224a.5.5 0 0 1 .223-.671z" />
                            </svg>
                        </div>
                        <div class="p-4 align-self-end">
                            Lorem ipsum dolor sit amet, consectetur adipisicing elit. Ipsum unde consequatur fuga fugit tempore laborum.
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>


    <!-- Container 3 Friends Cover-->
    <section id="container-3">
        <div class="dark-overlay-friends">
            <div class="container">
                <div class="row">
                    <div class="col text-center">
                        <div class="p-5">
                            <h1 class="display-4">
                                Find Your Friends
                            </h1>
                            <hr class="accent-white">
                            <p class="lead">
                                Do you ever wanted to know what products your friends buy? Well, now you can! With our great privacy compliant search feature.
                            </p>
                            <a class="btn btn-outline-light" href="friends.php">Search for your Friends</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>


    <!-- Container 4 Friends Text-->
    <section id="container-4" class="text-muted py-5">
        <div class="container">
            <div class="row">
                <div class="col-md-6">
                    <h3 class="green">Headline about your friends</h3>
                    <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Voluptatum impedit tempora cumque non corrupti fuga quibusdam numquam laudantium similique natus consequuntur consectetur quo qui officia in, modi dolores expedita saepe?</p>
                    <div class="d-flex flex-row">
                        <div class="p-4 align-self-start">
                            <svg width="2em" height="2em" viewBox="0 0 16 16" class="bi bi-chevron-compact-right" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                                <path fill-rule="evenodd" d="M6.776 1.553a.5.5 0 0 1 .671.223l3 6a.5.5 0 0 1 0 .448l-3 6a.5.5 0 1 1-.894-.448L9.44 8 6.553 2.224a.5.5 0 0 1 .223-.671z" />
                            </svg>
                        </div>
                        <div class="p-4 align-self-end">
                            Lorem ipsum dolor sit amet, consectetur adipisicing elit. Ipsum unde consequatur fuga fugit tempore laborum.
                        </div>
                    </div>
                    <div class="d-flex flex-row">
                        <div class="p-4 align-self-start">
                            <svg width="2em" height="2em" viewBox="0 0 16 16" class="bi bi-chevron-compact-right" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                                <path fill-rule="evenodd" d="M6.776 1.553a.5.5 0 0 1 .671.223l3 6a.5.5 0 0 1 0 .448l-3 6a.5.5 0 1 1-.894-.448L9.44 8 6.553 2.224a.5.5 0 0 1 .223-.671z" />
                            </svg>
                        </div>
                        <div class="p-4 align-self-end">
                            Lorem ipsum dolor sit amet, consectetur adipisicing elit. Ipsum unde consequatur fuga fugit tempore laborum.
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
    <section id="container-5">
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
    <section id="container-6" class="text-muted py-5">
        <div class="container">
            <div class="row">
                <div class="col-md-6">
                    <img class="img-fluid mb-3 rounded-circle" src="../assets/img/question.jpg" alt="contact">
                </div>

                <div class="col-md-6">
                    <h3 class="green">Headline about the support</h3>
                    <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Voluptatum impedit tempora cumque non corrupti fuga quibusdam numquam laudantium similique natus consequuntur consectetur quo qui officia in, modi dolores expedita saepe?</p>
                    <div class="d-flex flex-row">
                        <div class="p-4 align-self-start">
                            <svg width="2em" height="2em" viewBox="0 0 16 16" class="bi bi-telephone-fill" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                                <path fill-rule="evenodd" d="M2.267.98a1.636 1.636 0 0 1 2.448.152l1.681 2.162c.309.396.418.913.296 1.4l-.513 2.053a.636.636 0 0 0 .167.604L8.65 9.654a.636.636 0 0 0 .604.167l2.052-.513a1.636 1.636 0 0 1 1.401.296l2.162 1.681c.777.604.849 1.753.153 2.448l-.97.97c-.693.693-1.73.998-2.697.658a17.47 17.47 0 0 1-6.571-4.144A17.47 17.47 0 0 1 .639 4.646c-.34-.967-.035-2.004.658-2.698l.97-.969z" />
                            </svg>
                        </div>
                        <div class="p-4 align-self-end">
                            Lorem ipsum dolor sit amet, consectetur adipisicing elit. Ipsum unde consequatur fuga fugit tempore laborum.
                        </div>
                    </div>
                    <div class="d-flex flex-row">
                        <div class="p-4 align-self-start">
                            <svg width="2em" height="2em" viewBox="0 0 16 16" class="bi bi-clipboard" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                                <path fill-rule="evenodd" d="M4 1.5H3a2 2 0 0 0-2 2V14a2 2 0 0 0 2 2h10a2 2 0 0 0 2-2V3.5a2 2 0 0 0-2-2h-1v1h1a1 1 0 0 1 1 1V14a1 1 0 0 1-1 1H3a1 1 0 0 1-1-1V3.5a1 1 0 0 1 1-1h1v-1z" />
                                <path fill-rule="evenodd" d="M9.5 1h-3a.5.5 0 0 0-.5.5v1a.5.5 0 0 0 .5.5h3a.5.5 0 0 0 .5-.5v-1a.5.5 0 0 0-.5-.5zm-3-1A1.5 1.5 0 0 0 5 1.5v1A1.5 1.5 0 0 0 6.5 4h3A1.5 1.5 0 0 0 11 2.5v-1A1.5 1.5 0 0 0 9.5 0h-3z" />
                            </svg>
                        </div>
                        <div class="p-4 align-self-end">
                            Lorem ipsum dolor sit amet, consectetur adipisicing elit. Ipsum unde consequatur fuga fugit tempore laborum.
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <?php
    // Load shop footer
    require(FOOTER_SHOP);
    // Load JavaScript
    require_once(JS_BOOTSTRAP); // Default Bootstrap JavaScript
    require_once(JS_SHOP); // Custom JavaScript
    if ($unlocked) {
        echo "<script>$('#greeting').modal('show')</script>";
    }

    if ($_SESSION['pwdChangeReminder']) {
        echo "<script>$('#pwd-change-reminder').modal('show')</script>";
    }
    ?>
</body>

</html>