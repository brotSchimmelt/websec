<nav id="sidebarMenu" class="col-md-3 col-lg-2 d-md-block bg-light sidebar collapse">
    <div class="sidebar-sticky pt-3">
        <h6 class="sidebar-heading d-flex justify-content-between align-items-center px-3 mt-4 mb-1 text-muted">
            <span>Student Affairs</span>
        </h6>
        <ul class="nav flex-column">
            <li class="nav-item">
                <a class="nav-link <?= ($here == "dashboard") ? "active" : ""; ?>" href="dashboard.php">
                    <span data-feather="home"></span>
                    Dashboard <span class="sr-only">(current)</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link <?= ($here == "results") ? "active" : ""; ?>" href="results.php">
                    <span data-feather="file"></span>
                    Results
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link <?= ($here == "export") ? "active" : ""; ?>" href="export.php">
                    <span data-feather="bar-chart-2"></span>
                    Export Data
                </a>
            </li>
        </ul>

        <h6 class="sidebar-heading d-flex justify-content-between align-items-center px-3 mt-4 mb-1 text-muted">
            <span>Technical Settings</span>
        </h6>
        <ul class="nav flex-column mb-2">
            <li class="nav-item">
                <a class="nav-link <?= ($here == "shop_settings") ? "active" : ""; ?>" href="shop_settings.php">
                    <span data-feather="settings"></span>
                    Shop Settings
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="http://localhost:8081" target="_blank">
                    <span data-feather="settings"></span>
                    phpMyAdmin Login
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="http://localhost:8080" target="_blank">
                    <span data-feather="settings"></span>
                    phpMyAdmin Shop
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="info.php" target="_blank">
                    <span data-feather="settings"></span>
                    info.php
                </a>
            </li>
        </ul>
    </div>
</nav>