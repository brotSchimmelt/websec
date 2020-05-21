<?php

require_once("libraries/password_compatibility_library.php");
require_once("config/db.php");
require_once("classes/Login.php");
require_once("functions.php");
include 'header.php';
$login = new Login();

if ($login->isUserLoggedIn()){
        echo 'Hey ' . $_SESSION['user_name'] . ',<br><br>';
		echo 'here is an overview of what you can do and your accessible hacksites:<br>';
	if(isUserUnlocked()) {
        echo '<ul style="list-style-type:square;">';
        echo '<li><a href="instructions.php">read the instructions</a></li>';
        echo '<br>';
        echo '<li><a href="simplexss.php">reflective XSS</a></li>';
        echo '<li><a href="storedxss.php">stored XSS</a></li>';
        echo '<br>';
        echo '<li><a href="sqlinjection.php">SQLi</a></li>';
        echo '<br>';
        echo '<li><a href="resetdb.php">reset database</a></li>';
        echo '<br>';
        echo '<li><a href="scorecard.php">view scorecard</a></li>';
        echo '<br>';
        echo '<li><a href="crosspost.php">contact support</a></li>';
	echo '<br>';
        if (isUserAdmin()) {
		echo '<li><a href="allscores.php">scores overview</a></li><br>';
	}
        echo '<li><a href="index.php?logout">logout</a></li>';
        echo '</ul>';
	} else {
        echo '<ul style="list-style-type:square;">';
        echo '<li><a href="instructions.php">read the instructions</a> (do this first!)</li>';
        echo '<br>';
        echo '<li>reflective XSS</li>';
        echo '<li>stored XSS</li>';
        echo '<br>';
        echo '<li>SQLi</li>';
        echo '<br>';
        echo '<li>reset database</li>';
        echo '<br>';
        echo '<li>view scorecard</li>';
        echo '<br>';
        echo '<li>contact support</li>';
	echo '<br>';
        echo '<li><a href="index.php?logout">logout</a></li>';
        echo '</ul>';
	}
} else {
        echo "You are not authorized!";
}

?>
