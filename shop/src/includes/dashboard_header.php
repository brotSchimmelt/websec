<?php
session_start();
?>

<head>
    <meta charset="utf-8">

    <title>WebSec | Admin</title>

    <!-- Bootstrap core CSS -->
    <link rel="stylesheet" href="../assets/css/bootstrap.css">
    <!-- Custom styles for this template -->
    <link href="../assets/css/dashboard.css" rel="stylesheet">
</head>

<body>
    <nav class="navbar navbar-dark sticky-top bg-dark flex-md-nowrap p-0 shadow">
        <a class="navbar-brand col-md-3 col-lg-2 mr-0 px-3" href="#">WebSec Admin Dashboard</a>
        <!-- <button class="navbar-toggler position-absolute d-md-none collapsed" type="button" data-toggle="collapse" data-target="#sidebarMenu" aria-controls="sidebarMenu" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button> -->
        <ul class="navbar-nav px-3">
            <li class="nav-item text-nowrap">
                <a class="nav-link" href="">Welcome, [USERNAME]</a>
            </li>
        </ul>
        <ul class="nav flex-row">
            <li class="nav-item text-nowrap">
                <a class="nav-link" href="#">Back to the Shop</a>
            </li>
            <li class="nav-item text-nowrap">
                <a class="nav-link" href="#">Documentation</a>
            </li>
            <li class="nav-item text-nowrap">
                <a class="nav-link" href="#">Logout</a>
            </li>
        </ul>
    </nav>
</body>