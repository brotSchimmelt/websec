<?php
require_once("libraries/password_compatibility_library.php");
require_once("config/db.php");
require_once("classes/Login.php");
require_once("functions.php");
include 'header.php';
$login = new Login();

if ($login->isUserLoggedIn() == true) {
        echo 'Hey ' . $_SESSION['user_name'] . ',<br><br>';
  if(!isset($_POST['readtheinstructions'])){
	include("userinstructions.html");
	}


  if(isset($_POST['readtheinstructions'])){
	unlockuser();
	echo "initiating your account.. ";
	# init everything
		$mysqli = new mysqli("localhost", "phplogin", "6Zhn5Tgb", "phplogin");
		if (mysqli_connect_errno()) {
				printf("Connect failed: %s\n", mysqli_connect_error());
				exit();
		}
		# transfer users to cookie ids table
		$allusers = array();
		$userids = array();
		$query = "SELECT user_id,user_name FROM phplogin.users;";
		if ($result = $mysqli->query($query)) {
				while ($row = $result->fetch_assoc()) {
						$allusers[] = $row['user_name'];
						$userids[$row['user_name']] = $row['user_id'];
				}
				$result->close();
		}
		$mysqli->close();
		$mysqli = new mysqli("localhost", "phplogin", "6Zhn5Tgb", "websec");
		if (mysqli_connect_errno()) {
				printf("Connect failed: %s\n", mysqli_connect_error());
				exit();
		}
		$cookieusers = array();
		$query = "SELECT username FROM websec.fake_cookie_ids;";
		if ($result = $mysqli->query($query)) {
				while ($row = $result->fetch_assoc()) {
						$cookieusers[] = $row['username'];
				}
				$result->close();
		}
		foreach ($allusers as $user){
				if (!in_array($user,$cookieusers)){
						$fakeid = $userids[$user] . md5($user);
						$insertquery = "INSERT INTO websec.fake_cookie_ids (username,fake_id) VALUES ('".$user."','".$fakeid."');";
						if ($mysqli->query($insertquery) === TRUE) {
								#echo "updating..";
						} else {
								echo "update error (ofla): " . $mysqli->error;
						}
				}
		}

		# delete comments in stored xss
		$deletequery = "DELETE FROM websec.xsscomments WHERE username = '".$_SESSION['user_name']."';";
		if ($mysqli->query($deletequery) === TRUE) {
		} else {
			echo "cleaning error (ksmo): " . $mysqli->error;
		}

		# delete posts in crossposts table
		$deletequery = "DELETE FROM websec.crossposts WHERE username = '".$_SESSION['user_name']."';";
		if ($mysqli->query($deletequery) === TRUE) {
			} else {
				echo "cleaning error (ksmp): " . $mysqli->error;
			}

		# create user database
		$user = $_SESSION['user_name'];
		$dbname = 'databases/'.slug($user).'.sqlite';
		if (file_exists($dbname)) {
				unlink($dbname);
		}
		$database = new SQLite3($dbname);
		if($database){
				$thisusername = "-1";
				$thisuserpassword = "-1";
				$thisuseremail = "-1";
				$query = "SELECT user_name,user_password_hash,user_email FROM phplogin.users WHERE user_name = '".$_SESSION['user_name']."';";
				if ($result = $mysqli->query($query)) {
						while ($row = $result->fetch_assoc()) {
								$thisusername = $row['user_name'];
								$thisuserpassword = $row['user_password_hash'];
								$thisuseremail = $row['user_email'];
						}
						$result->close();
				}
				$database->exec('CREATE TABLE users (username text NOT NULL, password text, email text, role text NOT NULL);');
				$database->exec("INSERT INTO users (username,password,email,role) VALUES ('admin','admin','admin@admin.admin','admin');");
				$database->exec("INSERT INTO users (username,password,email,role) VALUES ('misterx','scotland','misterx@scot.land','user');");
				$database->exec("INSERT INTO users (username,password,email,role) VALUES ('girly95','pompidou','girly95@hotmail.con','user');");
				$database->exec("INSERT INTO users (username,password,email,role) VALUES ('".$thisusername."','".$thisuserpassword."','".$thisuseremail."','user');");
				$query = 'INSERT INTO websec.userdbs (`username`,`dbname`) VALUES ("'.$user.'","'.$dbname.'") ON DUPLICATE KEY UPDATE `dbname` = "'.$dbname.'";';
				if ($mysqli->query($query) === TRUE) {
						#echo "updating..";
				} else {
						echo "update error (kdtz): " . $mysqli->error;
				}
		} else {
				die($sqliteerror);
		}
		$database->close();
		$mysqli->close();
	echo "ok.";
	echo '<h2>You have been unlocked!</h2>Happy hacking!';
	#echo '<script type="text/javascript">setTimeout(function(){location.href="resetdb.php"} , 5000);</script>';


  }
	?>
	<br><br>
        <h4>CONFIRMATION</h4>
        I have read the instructions carefully.<br>
	I am aware that I am responsible for my actions.<br>
	I will not take any actions to harm or attack any system that is not meant for it.<br>
        <br><br>
	<form action="instructions.php" method="post">
        your name:
        <input type="text" name="username" value="<?php echo $_SESSION['user_name']; ?>" disabled><br>
        <input type="hidden" name="readtheinstructions" value="1">
        <br>
        <?php
	if(isUserUnlocked()){
		echo '<input type="checkbox" name="okay" value="okay" checked disabled>I have read the instruction and agreed<br>';
	} else {
		echo '<input type="submit" value="I agree to the terms and instructions">';
	}
	?>
        </form>
	<?php

} else {
        echo "Something went wrong..";
}
echo '<br><br><br><a href="overview.php">back to overview</a>';
?>
