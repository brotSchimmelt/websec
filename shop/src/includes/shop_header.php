<?php
$num = get_number_of_cart_items();
?>
<nav class="site-header sticky-top py-1" id="main-navbar">
    <div class="container d-flex flex-column flex-md-row justify-content-between">
        <a class="py-2d-none d-md-inline-block" href="main.php" aria-label="Main Page">
            <img class="mt-1 mb-2" src="/assets/img/wwu_cysec_inverted.png" width="90" height="40">
        </a>
        <a class="py-2 d-none d-md-inline-block mt-2" href="main.php">Home</a>
        <a class="py-2 d-none d-md-inline-block mt-2" href="overview.php">Products</a>
        <a class="py-2 d-none d-md-inline-block mt-2" href="friends.php">Find Your Friends</a>
        <a class="py-2 d-none d-md-inline-block mt-2" href="contact.php">Support</a>
        <a class="py-2 d-none d-md-inline-block mt-2" href="cart.php">
            <span>Cart</span>
            <?php if ($num > 0) : ?>
                <span class="badge badge-danger"><?= $num < 100 ? $num : "99+" ?></span>
            <?php endif ?>
        </a>

        <a class="nav-link dropdown-toggle mt-2" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            <svg width="1.5em" height="1.5em" viewBox="0 0 16 16" class="bi bi-person-circle pb-1" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                <path d="M13.468 12.37C12.758 11.226 11.195 10 8 10s-4.757 1.225-5.468 2.37A6.987 6.987 0 0 0 8 15a6.987 6.987 0 0 0 5.468-2.63z" />
                <path fill-rule="evenodd" d="M8 9a3 3 0 1 0 0-6 3 3 0 0 0 0 6z" />
                <path fill-rule="evenodd" d="M8 1a7 7 0 1 0 0 14A7 7 0 0 0 8 1zM0 8a8 8 0 1 1 16 0A8 8 0 0 1 0 8z" />
            </svg>
            Account
        </a>
        <div class="dropdown-menu" aria-labelledby="#navbarDropdown">
            <a class="dropdown-item" href="scoreboard.php">Scoreboard</a>
            <a class="dropdown-item" href="reset_db.php">Reset Database</a>
            <a class="dropdown-item" href="help.php">Help</a>
            <a class="dropdown-item" href="admin.php">Admin</a>
            <a class="dropdown-item" href="/logout.php?token=<?= $_SESSION['userToken'] ?>">Logout</a>
        </div>
    </div>
</nav>