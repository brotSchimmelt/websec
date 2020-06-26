<nav class="navbar navbar-dark sticky-top bg-dark flex-md-nowrap p-0 shadow">
    <a class="navbar-brand col-md-3 col-lg-2 mr-0 px-3" href="#">WebSec Admin Dashboard</a>
    <!-- <button class="navbar-toggler position-absolute d-md-none collapsed" type="button" data-toggle="collapse" data-target="#sidebarMenu" aria-controls="sidebarMenu" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button> -->
    <ul class="navbar-nav px-3">
        <li class="nav-item text-nowrap">
            <a class="nav-link" href="">Welcome, <?= $username ?>!</a>
        </li>
    </ul>
    <ul class="nav flex-row">
        <li class="nav-item text-nowrap">
            <a class="nav-link" href="../shop/main.php">Back to the Shop</a>
        </li>
        <li class="nav-item text-nowrap">
            <a class="nav-link" href="documentation.php">Documentation</a>
        </li>
        <li class="nav-item text-nowrap">
            <!-- <a class="nav-link" href="#">Logout</a> -->
            <a class="btn btn-outline-warning btn-sm" href="/logout.php?token=<?= $_SESSION['userToken'] ?>">Logout</a>
        </li>
    </ul>
</nav>