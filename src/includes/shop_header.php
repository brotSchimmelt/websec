<?php
// load DB config files
require_once(CONF_DB_SHOP);
require_once(CONF_DB_LOGIN);

// load functions
require_once(FUNC_WEBSEC);
require_once(FUNC_SHOP);
require_once(FUNC_LOGIN);
require_once(FUNC_BASE);

$num = get_number_of_cart_items();
$username = $_SESSION['userName'];

// check SQLi and stored XSS challenge status
$sqliSolved = lookup_challenge_status("sqli", $_SESSION['userName']);
$storedXSSSolved = lookup_challenge_status("stored_xss", $_SESSION['userName']);

// check if the stored XSS cookie was set
if (!$storedXSSSolved) {

    if (compare_xss_cookies()) {
        set_stolen_session($_SESSION['userName']);
        $username = "Elliot";
    }
}

// check if stored xss challenges was solved and the banana slicer was added
if (isset($_SESSION['fakeCart']) && $_SESSION['fakeCart'] == true) {
    check_stored_xss_challenge($_SESSION['userName']);
}

// check if username is too long for the menu bar
if (mb_strlen($username) >= 20) {
    $accountName = mb_substr($username, 0, 16) . " ...";
} else {
    $accountName = $username;
}

// set new color theme if user is premium user
$color = ($sqliSolved) ? "rgba(145, 174, 100, 1)" : "rgba(46, 109, 134, 1)";
?>
<style>
    .dropdown-menu {
        background-color: <?= $color ?> !important;
    }

    .site-header {
        background-color: <?= $color ?> !important;
    }
</style>

<nav class="navbar navbar-expand-md site-header sticky-top py-1" id="main-navbar">
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbar-shop" aria-controls="navbar-shop" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon">
            <svg width="2em" height="2em" viewBox="0 0 16 16" class="bi bi-list text-white pb-2" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                <path fill-rule="evenodd" d="M2.5 11.5A.5.5 0 0 1 3 11h10a.5.5 0 0 1 0 1H3a.5.5 0 0 1-.5-.5zm0-4A.5.5 0 0 1 3 7h10a.5.5 0 0 1 0 1H3a.5.5 0 0 1-.5-.5zm0-4A.5.5 0 0 1 3 3h10a.5.5 0 0 1 0 1H3a.5.5 0 0 1-.5-.5z" />
            </svg>
        </span>
    </button>
    <a class="navbar-brand d-none d-lg-block" href="/shop/main.php">WebSec Shop</a>
    <a class="navbar-brand d-md-none" href="/shop/main.php">WebSec Shop</a>

    <div class="collapse navbar-collapse mb-2" id="navbar-shop">
        <ul class="navbar-nav mr-auto mt-2 mt-lg-0 container d-flex flex-column flex-md-row justify-content-between">
            <li class="nav-item d-none d-md-block">
                <a class="py-2 d-inline-block mt-2" href="/shop/main.php">Home</a>
            </li>
            <li class="nav-item">
                <a class="py-2 d-inline-block mt-2" href="/shop/overview.php">Products</a>
            </li>
            <li class="nav-item">
                <a class="py-2 d-inline-block mt-2" href="/shop/friends.php">Find Your Friends</a>
            </li>
            <li class="nav-item">
                <a class="py-2 d-inline-block mt-2" href="/shop/contact.php">Support</a>
            </li>
            <li class="nav-item">
                <a class="py-2 d-inline-block mt-2" href="/shop/cart.php">
                    <span>Cart</span>
                    <?php if ($num > 0) : ?>
                        <span class="badge badge-danger"><?= $num < 100 ? $num : "99+" ?></span>
                    <?php endif; ?>
                </a>
            </li>
            <li class="nav-item d-md-none">
                <a class="py-2 d-inline-block mt-2" href="/user/scorecard.php">Scorecard</a>
            </li>
            <li class="nav-item d-md-none">
                <a class="py-2 d-inline-block mt-2" href="/user/challenge_settings.php">Challenge Settings</a>
            </li>
            <li class="nav-item d-md-none">
                <a class="py-2 d-inline-block mt-2" href="/user/help.php">Help</a>
            </li>
            <li class="nav-item d-md-none">
                <a class="py-2 d-inline-block mt-2" href="/user/change_password.php">Change Password</a>
            </li>
            <?=
                (is_user_admin()) ?
                    '<li class="nav-item d-md-none">
                <a class="py-2 d-inline-block mt-2" href="/user/admin.php">Admin</a>
            </li>' : ""
            ?>
            <li class="nav-item d-md-none">
                <a class="py-2 d-inline-block mt-2" href="/logout.php?token=<?= $_SESSION['userToken'] ?>">Logout</a>
            </li>
            <li class="nav-item d-none d-md-block">
                <div class="btn-group">


                    <a class="nav-link dropdown-toggle mt-2" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <?php if (!$sqliSolved) : ?>
                            <svg width="1.5em" height="1.5em" viewBox="0 0 16 16" class="bi bi-person-circle pb-1" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                                <path d="M13.468 12.37C12.758 11.226 11.195 10 8 10s-4.757 1.225-5.468 2.37A6.987 6.987 0 0 0 8 15a6.987 6.987 0 0 0 5.468-2.63z" />
                                <path fill-rule="evenodd" d="M8 9a3 3 0 1 0 0-6 3 3 0 0 0 0 6z" />
                                <path fill-rule="evenodd" d="M8 1a7 7 0 1 0 0 14A7 7 0 0 0 8 1zM0 8a8 8 0 1 1 16 0A8 8 0 0 1 0 8z" />
                            </svg>
                        <?php else : ?>
                            <svg width="1.5em" height="1.5em" viewBox="0 0 16 16" class="bi bi-star-fill pb-1 text-warning" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                                <path d="M3.612 15.443c-.386.198-.824-.149-.746-.592l.83-4.73L.173 6.765c-.329-.314-.158-.888.283-.95l4.898-.696L7.538.792c.197-.39.73-.39.927 0l2.184 4.327 4.898.696c.441.062.612.636.283.95l-3.523 3.356.83 4.73c.078.443-.36.79-.746.592L8 13.187l-4.389 2.256z" />
                            </svg>
                        <?php endif; ?>
                        <?= $accountName ?>
                    </a>
                    <div class="dropdown-menu dropdown-menu-right" aria-labelledby="#navbarDropdown">
                        <h6 class="dropdown-header text-white font-weight-bold text-center">Account</h6>
                        <a class="dropdown-item" href="/user/scorecard.php">Scorecard</a>
                        <a class="dropdown-item" href="/user/challenge_settings.php">Challenge Settings</a>
                        <a class="dropdown-item" href="/user/change_password.php">Change Password</a>
                        <a class="dropdown-item" href="/user/help.php">Help</a>
                        <div class="dropdown-divider"></div>
                        <?= (is_user_admin()) ? '<a class="dropdown-item" href="/user/admin.php">Admin</a><div class="dropdown-divider"></div>' : "" ?>
                        <a class="dropdown-item" href="/logout.php?token=<?= $_SESSION['userToken'] ?>">Logout</a>
                    </div>
                </div>
            </li>
        </ul>
    </div>
</nav>