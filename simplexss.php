<?php
require_once("libraries/password_compatibility_library.php");
require_once("config/db.php");
require_once("classes/Login.php");
require_once("functions.php");
include 'header.php';
$login = new Login();

if ($login->isUserLoggedIn() == true && isUserUnlocked()) {
        $mysqli = new mysqli("localhost", "websec", "S0s3zwo9z3hn", "websec");
        if (mysqli_connect_errno()) {
                printf("Connect failed: %s\n", mysqli_connect_error());
                exit();
        }
	$fake_id = 'youwerenotrecognized';
        $value = $_SESSION['user_name'];
        $query = "SELECT fake_id FROM fake_cookie_ids WHERE username = '".$value."'";
        if ($result = $mysqli->query($query)) {
                while ($row = $result->fetch_assoc()) {
                        $fake_id = $row["fake_id"];
                }
                $result->close();
        }
        $mysqli->close();
        setcookie("SessionCookieID", $fake_id);

        echo 'Hey ' . $_SESSION['user_name'] . ',<br><br>';

	?>

	<form action="simplexss.php" method="get">
	Search:
	<input type="text" name="searchfor" value="">
	<input type="submit" value="Submit">
	</form>

	<?php

	if(isset($_GET['searchfor'])) {
		$searchstring = $_GET['searchfor'];
		echo 'You searched for "'.$searchstring.'".<br>There were <strong>no</strong> results..';
	}

} else {
        echo "You are not authorized!";
}

echo '<br><br><br><a href="overview.php">back to overview</a>';


?>
