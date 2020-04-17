<?php
require_once("libraries/password_compatibility_library.php");
require_once("config/db.php");
require_once("classes/Login.php");
require_once("functions.php");
include 'header.php';
$login = new Login();

if ($login->isUserLoggedIn() == true && isUserUnlocked()) {
	$user = $_SESSION['user_name'];

	if(isset($_POST['searchuser'])){

		$mysqli = new mysqli("localhost", "websec", "S0s3zwo9z3hn", "websec");
        	if (mysqli_connect_errno()) {
                	printf("Connect failed: %s\n", mysqli_connect_error());
	                exit();
        	}
	        $userdatabasepath = "";
		$query = 'SELECT dbname FROM websec.userdbs WHERE username="'.$user.'";';
	        if ($result = $mysqli->query($query)) {
	                while ($row = $result->fetch_assoc()) {
				$userdatabasepath = $row['dbname'];
	                }
	                $result->close();
	        } else {
			echo "error! (gtrf)";
		}
		$mysqli->close();

		$searchuser = $_POST['searchuser'];
		$database = new SQLite3($userdatabasepath);
		if($database){
			$searchquery = 'SELECT username,email FROM users WHERE username="'.$searchuser.'";';
			#echo 'DEBUG: here is your sql query: '.$searchquery.'<br>';
			$queries = explode(';', $searchquery);
			foreach ($queries as $q){
				$pos1 = strpos($q,"SELECT");
				$pos2 = strpos($q,"INSERT");
				if ($pos1 === false && $pos2 === false ) {
					#echo "error: query not acceptable! $q";
				} else {
					$result = $database->query($q);
					while ($row = $result->fetchArray()) {
						echo "<h4>User found:</h4>There is a user with that name in our community.<br>Here is the data:<br><br>";
 						#var_dump($row);
						#print_r($row);
						foreach ($row as $key => $value) {
							if (is_numeric($key)) {continue;}
							if ($key == "username" || $key == "email" || $key == "password" || $key == "role"){
								echo "$key = $value <br>";
							}
						}
						echo "<br><hr><br>";
					}
				}
			}
		} else {
			echo 'You seem to have an error in your SQL query: '.$searchquery;
			die($sqliteerror);
		}
		$database->close();
	}


        echo 'Hey ' . $_SESSION['user_name'] . ',';
        ?>
        <h4>Find Members</h4>
	You want to find other members of our community?<br>
	No problemo! Just use the following form:
	<br><br>
        <form action="sqlinjection.php" method="post">
        search a username:
        <input type="text" name="searchuser" size="50" value="">
        <input type="submit" value="Search User">
        </form>
	<br><font size="-1">Info: We value our users' privacy. If you entered a username in the search field and there is no corresponding user then nothing is displayed.</font>
        <?php


} else {
        echo "You are not authorized!";
}
echo '<br><br><a href="overview.php">back to overview</a>';
?>


