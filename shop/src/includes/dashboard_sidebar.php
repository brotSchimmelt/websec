<nav id="sidebarMenu" class="col-md-3 col-lg-2 d-md-block bg-light sidebar collapse">
    <div class="sidebar-sticky pt-3">
        <ul class="nav flex-column">
            <li class="nav-item">
                <a class="nav-link <?php echo ($here == "dashboard") ? "active" : ""; ?>" href="dashboard.php">
                    <span data-feather="home"></span>
                    Dashboard <span class="sr-only">(current)</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link <?php echo ($here == "user_management") ? "active" : ""; ?>" href="user_management.php">
                    <span data-feather="users"></span>
                    User Management
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link <?php echo ($here == "results") ? "active" : ""; ?>" href="results.php">
                    <span data-feather="file"></span>
                    Results
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link <?php echo ($here == "export_data") ? "active" : ""; ?>" href="export_data.php">
                    <span data-feather="bar-chart-2"></span>
                    Export Data
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link <?php echo ($here == "feedback") ? "active" : ""; ?>" href="feedback.php">
                    <span data-feather="file"></span>
                    Feedback
                </a>
            </li>
        </ul>

        <h6 class="sidebar-heading d-flex justify-content-between align-items-center px-3 mt-4 mb-1 text-muted">
            <span>Technical Settings</span>
        </h6>
        <ul class="nav flex-column mb-2">
            <li class="nav-item">
                <a class="nav-link <?php echo ($here == "shop_settings") ? "active" : ""; ?>" href="shop_settings.php">
                    <span data-feather="settings"></span>
                    Shop Settings
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="http://localhost:1234" target="_blank">
                    <span data-feather="settings"></span>
                    phpMyAdmin
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="info.php" target="_blank">
                    <span data-feather="settings"></span>
                    info.php
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="#">
                    <span data-feather="settings"></span>
                    Log Files
                </a>
            </li>
        </ul>
    </div>
</nav>