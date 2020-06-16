<html lang="en">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="../assets/css/bootstrap.css">

    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Open+Sans&family=Roboto:wght@300&display=swap" rel="stylesheet">

    <!-- Custom CSS to overwrite Bootstrap.css -->
    <link rel="stylesheet" href="../assets/css/custom.css">

    <title>WebSec Shop</title>
</head>


<body>
    <nav class="navbar navbar-expand-md navbar-dark bg-dark" id="default-nav">
        <div class="navbar-collapse collapse">
            <a class="mx-auto navbar-brand" href="#"><img class="mb-4" src="../assets/img/wwu_cysec.png" width="140" height="75"></a>
        </div>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#collapsedNavBar">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="navbar-collapse collapse">
            <ul class="navbar-nav mx-auto">
                <li class="nav-item">
                    <a href="main.php" class="nav-link">Home</a>
                </li>
                <li class="nav-item">
                    <a href="product.php" class="nav-link">Products</a>
                </li>
                <li class="nav-item">
                    <a href="friends.php" class="nav-link">Find Your Friends</a>
                </li>
                <li class="nav-item">
                    <a href="contact.php" class="nav-link">Contact</a>
                </li>
            </ul>
        </div>
        <div class="navbar-collapse collapse">
            <ul class="navbar-nav mx-auto">
                <li class="nav-item">
                    <a href="cart.php" class="nav-link">Shopping Cart</a>
                </li>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        Account
                    </a>
                    <div class="dropdown-menu" aria-labelledby="#navbarDropdown">
                        <a class="dropdown-item" href="scoreboard.php">Scoreboard</a>
                        <a class="dropdown-item" href="reset_db.php">Reset Database</a>
                        <a class="dropdown-item" href="help.php">Help</a>
                        <a class="dropdown-item" href="admin.php">Admin</a>
                        <a class="dropdown-item" href="/logout.php?token=<?= $_SESSION['userToken'] ?>">Logout</a>
                    </div>
                </li>
            </ul>
        </div>
        <div class="collapse" id="collapsedNavBar">
            <div>
                <a class="navbar-brand" href="main.php">WebSec Shop</a>
            </div>
            <ul class="navbar-nav mx-auto">
                <li class="nav-item">
                    <a href="product.php" class="nav-link">Products</a>
                </li>
                <li class="nav-item">
                    <a href="#" class="nav-link">Find Your Friends</a>
                </li>
                <li class="nav-item">
                    <a href="contact.php" class="nav-link">Contact</a>
                </li>
                <li class="nav-item">
                    <a href="cart.php" class="nav-link">Shopping Cart</a>
                </li>
                <li class="nav-item">
                    <a href="scoreboard.php" class="nav-link">Scoreboard</a>
                </li>
                <li class="nav-item">
                    <a href="reset_db.php" class="nav-link">Reset Database</a>
                </li>
                <li class="nav-item">
                    <a href="help.php" class="nav-link">Help</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="/logout.php?token=<?= $_SESSION['userToken'] ?>">Logout</a>
                </li>
            </ul>
        </div>
    </nav>
</body>