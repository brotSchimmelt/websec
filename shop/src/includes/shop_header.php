<?php
$num = get_number_of_cart_items();
if (isset($_SESSION['sqliSolved'])) {
    $color = "rgba(145, 174, 100, 1)";
    $premiumAccount = "PREMIUM ACCOUNT";
} else {
    $color = "rgba(46, 109, 134, 1)";
    $premiumAccount = "Account";
}
?>

<style>
    .dropdown-menu {
        background-color: <?= $color ?> !important;
    }

    .site-header {
        background-color: <?= $color ?> !important;
    }
</style>

<nav class="site-header sticky-top py-1" id="main-navbar">
    <div class="container d-flex flex-column flex-md-row justify-content-between">
        <a class="py-2d-none d-md-inline-block" href="/shop/main.php" aria-label="Main Page">
            <img class="mt-1 mb-2" src="/assets/img/wwu_cysec.png" width="90" height="40">
        </a>
        <a class="py-2 d-none d-md-inline-block mt-2" href="/shop/main.php">Home</a>
        <a class="py-2 d-none d-md-inline-block mt-2" href="/shop/overview.php">Products</a>
        <a class="py-2 d-none d-md-inline-block mt-2" href="/shop/friends.php">Find Your Friends</a>
        <a class="py-2 d-none d-md-inline-block mt-2" href="/shop/contact.php">Support</a>
        <a class="py-2 d-none d-md-inline-block mt-2" href="/shop/cart.php">
            <span>Cart</span>
            <?php if ($num > 0) : ?>
                <span class="badge badge-danger"><?= $num < 100 ? $num : "99+" ?></span>
            <?php endif; ?>
        </a>

        <a class="nav-link dropdown-toggle mt-2" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            <?php if (!isset($_SESSION['sqliSolved'])) : ?>
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
            <?= $premiumAccount ?>
        </a>
        <div class="dropdown-menu" aria-labelledby="#navbarDropdown">
            <a class="dropdown-item" href="/user/scoreboard.php">Scoreboard</a>
            <a class="dropdown-item" href="/user/reset_db.php">Reset Database</a>
            <a class="dropdown-item" href="/user/change_password.php">Change Password</a>
            <a class="dropdown-item" href="/shop/help.php">Help</a>
            <a class="dropdown-item" href="/user/admin.php">Admin</a>
            <a class="dropdown-item" href="/logout.php?token=<?= $_SESSION['userToken'] ?>">Logout</a>
        </div>
    </div>
</nav>