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
	$sessionusername = $_SESSION['user_name'];
	$solvedxss = False;
	$solvedsqli = False;
	$solvedcrosspost = False;
	$solvedcrosspostdoublecheck = False;
	
	$fake_id = 'youwerenotrecognized';
	
        $query = "SELECT fake_id FROM fake_cookie_ids WHERE username = '".$sessionusername."'";
        if ($result = $mysqli->query($query)) {
                while ($row = $result->fetch_assoc()) {
                        $fake_id = $row["fake_id"];
                }
                $result->close();
        }
	
        $query = "SELECT `comment` FROM xsscomments WHERE username = '".$sessionusername."'";
        if ($result = $mysqli->query($query)) {
                while ($row = $result->fetch_assoc()) {
						$haystack = htmlspecialchars(mysqli_real_escape_string($mysqli, $row["comment"]));
						#if (preg_match("/alert\([\'\"]$fake_id/i", $haystack)) {
						if (preg_match("/alert\(.*$fake_id.*\)/i", $haystack)) {
							$solvedxss = True;	# if needle is in haystack (fake_id in comments), challenge is solved
						}
                }
                $result->close();
        }
	
	$query = "SELECT `post` FROM crossposts WHERE username = '".$sessionusername."'";
        if ($result = $mysqli->query($query)) {
                while ($row = $result->fetch_assoc()) {
						$haystack = htmlspecialchars(mysqli_real_escape_string($mysqli, $row["post"]));
						// echo $haystack."<br>";
						$needle   = "pwned";
						if( strpos( $haystack, $needle ) !== false) {
							$solvedcrosspost = True;
						}
                }
                $result->close();
        }
	
	/*
	$query = "SELECT `referrer` FROM crossposts WHERE username = '".$sessionusername."'";
        if ($result = $mysqli->query($query)) {
                while ($row = $result->fetch_assoc()) {
						$haystack = htmlspecialchars(mysqli_real_escape_string($mysqli, $row["referrer"]));
						// echo $haystack."<br>";
						$needle1   = "simplexss";
						$needle2   = "storedxss";
						if (strpos($haystack, $needle1) !== false || strpos($haystack, $needle1) !== false ) {
							$solvedcrosspostdoublecheck = True;
						}
                }
                $result->close();
        }
	*/
	
	$query = "SELECT `referrer` FROM crossposts WHERE username = '".$sessionusername."'";
	if ($result = $mysqli->query($query)) {
		while ($row = $result->fetch_assoc()) {
			$haystack = htmlspecialchars(mysqli_real_escape_string($mysqli, $row["referrer"]));
			// echo $haystack."<br>";
			$needle1   = "simplexss";
			$needle2   = "storedxss";
			if (strpos($haystack, $needle1) !== false || strpos($haystack, $needle2) !== false ) {
				$solvedcrosspostdoublecheck = True;
			}
		}
		$result->close();
	}
	
	$userdatabasepath = "";
	$query = 'SELECT dbname FROM websec.userdbs WHERE username="'.$sessionusername.'";';
        if ($result = $mysqli->query($query)) {
                while ($row = $result->fetch_assoc()) {
					$userdatabasepath = $row['dbname'];
                }
               $result->close();
	} else {
		echo "error! (gtrf2)";
	}
	$database = new SQLite3($userdatabasepath);
	if($database){
		$searchquery = 'SELECT * FROM users WHERE role="admin" and username != "admin";';
		$result = $database->query($searchquery);
		$row = $result->fetchArray();
		// $numRows = $row['count'];
		if ($row) { $solvedsqli = True; }
	} else {
		echo "Error in fetch ".$database->lastErrorMsg();
	}
	
        $mysqli->close();

        echo 'Hey ' . $_SESSION['user_name'] . ',<br><br>';

	?>
	<p>This scorecard is just an <em>indicator</em> of your challenges' status!<br>
	The final judgement whether or not a challenge was solved correctly is done by your lecturer.</p>
	<table class="minimalistBlack">
	<thead>
	<tr>
	<th>Challenge</th>
	<th>Status</th>
	</tr>
	</thead>
	<tbody>
	<tr>
	<td>XSS (combined)</td><?php if($solvedxss) { echo "<td class=\"green\">solved</td>"; } else { echo "<td class=\"red\">NOT SOLVED</td>"; } ?></tr>
	<tr>
	<td>SQLi</td><?php if($solvedsqli) { echo "<td class=\"green\">solved</td>"; } else { echo "<td class=\"red\">NOT SOLVED</td>"; } ?></tr>
	<tr>
	<td>Support Form Hack</td>
	<?php
	if($solvedcrosspost && $solvedcrosspostdoublecheck) { echo "<td class=\"green\">solved</td>"; }
	elseif ($solvedcrosspost || $solvedcrosspostdoublecheck) { echo "<td class=\"yellow\">probably solved</td>"; }
	else { echo "<td class=\"red\">NOT SOLVED</td>"; }
	?>
	</tr>
	</tbody>
	</tr>
	</table>
	
	<?php if($solvedxss && $solvedsqli && $solvedcrosspost) { include 'fireworks.html'; } ?>

	<?php

} else {
        echo "You are not authorized!";
}

echo '<br><br><br><a href="overview.php">back to overview</a>';


?>
