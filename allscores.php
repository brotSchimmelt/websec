<?php require_once("libraries/password_compatibility_library.php");
require_once("config/db.php");
require_once("classes/Login.php");
require_once("functions.php");
include 'header.php';
$login = new Login(); if ($login->isUserLoggedIn() == true && isUserUnlocked()) {
		echo 'Hey ' . $_SESSION['user_name'] . ',<br><br>';
		#$sessionusername = $_SESSION['user_name'];
		if (isUserAdmin()) {
			$sql = new mysqli("localhost", "phplogin", "6Zhn5Tgb", "phplogin");
			if (mysqli_connect_errno())
			{
				printf("Connect failed: %s\n", mysqli_connect_error());
				exit();
			}
			$query = "SELECT user_name FROM phplogin.users;";
			$result = $sql->query($query);
			if (!$result) {
				printf("Query failed: %s\n", $mysqli->error);
				exit;
			}
			while($row = $result->fetch_row()) {
				$usernames[]=$row[0];
			}
			$result->close();
			$sql->close();
			$numxsssolved = 0;
			$numsqlisolved = 0;
			$numcsrfsolved = 0;
			$numusers = 0;
			$scoressum = 0;
			$numchallengestotal = 3;
			$excludeusers = array("Administrator", "Tester", "Tester2", "HenryHosseini");

			?>

			<p>You are registered as administrator for this portal. So, here are all the scores:</p>
			<table class="minimalistBlack">
			<thead>
			<tr>
			<th>Name</th>
			<th>XSS (combined)</th>
			<th>SQLi</th>
			<th>Crosspost</th>
			<th>Score</th>
			</tr>
			</thead>
			<tbody>
			
			<?php
			foreach ($usernames as $username) {
				if (in_array($username, $excludeusers)) {
					continue;
				}
				#echo $username."<br>";
				$numusers++;
				$numchallengessolved = 0;
				$mysqli = new mysqli("localhost", "websec", "S0s3zwo9z3hn", "websec");
				if (mysqli_connect_errno()) {
						printf("Connect failed: %s\n", mysqli_connect_error());
						exit();
				}
				
				$solvedxss = False;
				$solvedsqli = False;
				$solvedcrosspost = False;
				$solvedcrosspostdoublecheck = False;
				$fake_id = 'youwerenotrecognized';
				
				$query = "SELECT fake_id FROM fake_cookie_ids WHERE username = '".$username."'";
				if ($result = $mysqli->query($query)) {
						while ($row = $result->fetch_assoc()) {
								$fake_id = $row["fake_id"];
						}
						$result->close();
				}
				
				$query = "SELECT `comment` FROM xsscomments WHERE username = '".$username."'";
				if ($result = $mysqli->query($query)) {
						while ($row = $result->fetch_assoc()) {
								$haystack = htmlspecialchars(mysqli_real_escape_string($mysqli, $row["comment"]));
								// echo htmlspecialchars($haystack);
                		                                #$needle1 = "alert";
        	                	                        #$needle2 = $fake_id;
	                                	                #if( strpos($haystack,$needle1) !== false && strpos($haystack,$needle2) !== false  ) {
								if (preg_match("/alert\(.*$fake_id.*\)/i", $haystack)) {
								#if (preg_match("/alert\([\'\"]$fake_id/i", $haystack)) {
                	                	                        $solvedxss = True;      # if needle is in haystack (fake_id in comments), challenge is solved
                        		                        }
								#$needle = "alert(\'".$fake_id."\')";
								#if( strpos( $haystack, $needle ) !== false) {
								#	$solvedxss = True;	# if needle is in haystack (fake_id in comments), challenge is solved
								#}
						}
						$result->close();
				}
				
				$query = "SELECT `post` FROM crossposts WHERE username = '".$username."'";
				if ($result = $mysqli->query($query)) {
						while ($row = $result->fetch_assoc()) {
								$haystack = htmlspecialchars(mysqli_real_escape_string($mysqli, $row["post"]));
								// echo $haystack."<br>";
								$needle = "pwned";
								if( strpos( $haystack, $needle ) !== false) {
									$solvedcrosspost = True;
								}
						}
						$result->close();
				}
				
				$query = "SELECT `referrer` FROM crossposts WHERE username = '".$username."'";
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
				$query = 'SELECT dbname FROM websec.userdbs WHERE username="'.$username.'";';
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
				
				if($solvedxss) { $numchallengessolved++; $numxsssolved++;}
				if($solvedsqli) { $numchallengessolved++; $numsqlisolved++; }
				if($solvedcrosspost) { $numchallengessolved++; $numcsrfsolved++; }
				$userscore = $numchallengessolved/$numchallengestotal;
				$scoressum += $userscore;

				?>
				<tr>
				<td><?php echo $username; ?></td>
				<?php if($solvedxss) { echo "<td class=\"green\">solved</td>"; } else { echo "<td class=\"red\">NOT SOLVED</td>"; } ?>
				<?php if($solvedsqli) { echo "<td class=\"green\">solved</td>"; } else { echo "<td class=\"red\">NOT SOLVED</td>"; } ?>
				<?php
			        if($solvedcrosspost && $solvedcrosspostdoublecheck) { echo "<td class=\"green\">solved</td>"; }
			        elseif ($solvedcrosspost || $solvedcrosspostdoublecheck) { echo "<td class=\"yellow\">probably solved</td>"; }
			        else { echo "<td class=\"red\">NOT SOLVED</td>"; }
			        ?>
				<td align="right"><?php echo number_format($userscore, 2, '.', ',')  ?></td>
				</tr>
				
				<?php
			}
			?>
			<tr>
			<td align="right">total</td>
			<td align="right"><?php echo $numxsssolved." / ".$numusers; ?></td>
			<td align="right"><?php echo $numsqlisolved." / ".$numusers; ?></td>
			<td align="right"><?php echo $numcsrfsolved." / ".$numusers; ?></td>
			<td align="right"><?php echo number_format($scoressum, 2, '.', ','); ?></td>
			</tr>
			</tbody>
			</table>
			<?php
		} else {
			echo "Restricted to admin users!";
		}
	} else {
        echo "You are not authorized!";
	}
echo '<br><br><br><a href="overview.php">back to overview</a>'; ?>
