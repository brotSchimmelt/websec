<nav class="navbar navbar-dark sticky-top bg-dark flex-md-nowrap p-0 shadow">
    <a class="navbar-brand col-md-3 col-lg-2 mr-0 px-3" href="dashboard.php">WebSec Admin</a>

    <ul class="navbar-nav px-3">
        <li class="nav-item text-nowrap">
            <a class="nav-link" href="">Welcome, <?= $username ?>!</a>
        </li>
    </ul>
    <ul class="nav flex-row">
        <li class="nav-item text-nowrap">
            <a class="nav-link text-muted" href="../shop/main.php">Back to the Shop</a>
        </li>
        <li class="nav-item text-nowrap">
            <a class="nav-link text-muted" href="documentation.php">Documentation</a>
        </li>
        <li class="nav-item text-nowrap">
            <a class="btn btn-outline-warning btn-sm mr-2 mt-1 mb-1 ml-3" href="/logout.php?token=<?= $_SESSION['userToken'] ?>">Logout</a>
        </li>
    </ul>
</nav>