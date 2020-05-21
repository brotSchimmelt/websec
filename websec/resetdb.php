<?php
require_once("libraries/password_compatibility_library.php");
require_once("config/db.php");
require_once("classes/Login.php");
require_once("functions.php");
include 'header.php';
$login = new Login();

if ($login->isUserLoggedIn() == true && isUserUnlocked()) {
  if(isset($_POST['doit'])) {
	$mysqli = new mysqli("localhost", "phplogin", "6Zhn5Tgb", "phplogin");
        if (mysqli_connect_errno()) {
                printf("Connect failed: %s\n", mysqli_connect_error());
                exit();
        }
	if(isset($_POST['simplexss'])){
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
	}

	if(isset($_POST['storedxss'])){
        # delete comments in stored xss
        $deletequery = "DELETE FROM websec.xsscomments WHERE username = '".$_SESSION['user_name']."';";
        if ($mysqli->query($deletequery) === TRUE) {
        } else {
                echo "cleaning error (ksmo): " . $mysqli->error;
        }
	}

	if(isset($_POST['crossposts'])){
        # delete posts in crossposts table
        $deletequery = "DELETE FROM websec.crossposts WHERE username = '".$_SESSION['user_name']."';";
        if ($mysqli->query($deletequery) === TRUE) {
	        } else {
        	        echo "cleaning error (ksmp): " . $mysqli->error;
        	}
	}

	if(isset($_POST['sqlinjection'])){
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
	}

	$mysqli->close();
	echo 'Hey ' . $_SESSION['user_name'] . ',<br><br>DB reset. Enjoy the freshness!';
	echo '<br><br><a href="resetdb.php">perform another reset</a>';

  } else {

        echo 'Hey ' . $_SESSION['user_name'] . ',';
        ?>
        <h4>RESET REFLECTIVE XSS</h4>
        This will <strong>delete all your achievements</strong>!<br>
        <form action="resetdb.php" method="post">
        your name:
        <input type="text" name="username" value="<?php echo $_SESSION['user_name']; ?>" disabled><br>
        <input type="hidden" name="doit" value="1">
		<input type="hidden" name="simplexss" value="1">
        <input type="submit" value="RESET REFLECTIVE XSS CHALLENGE">
        </form>
	<br><hr><br>

	<h4>RESET STORED XSS</h4>
        This will <strong>delete all your achievements</strong>!<br>
        <form action="resetdb.php" method="post">
        your name:
        <input type="text" name="username" value="<?php echo $_SESSION['user_name']; ?>" disabled><br>
        <input type="hidden" name="doit" value="1">
		<input type="hidden" name="storedxss" value="1">
        <input type="submit" value="RESET STORED XSS CHALLENGE">
        </form>
	<br><hr><br>

        <h4>RESET SQL DATABASE</h4>
        This will <strong>delete all your achievements</strong>!<br>
        <form action="resetdb.php" method="post">
        your name:
        <input type="text" name="username" value="<?php echo $_SESSION['user_name']; ?>" disabled><br>
        <input type="hidden" name="doit" value="1">
                <input type="hidden" name="sqlinjection" value="1">
        <input type="submit" value="RESET SQL DATABASE">
        </form>
	<br><hr><br>

	<h4>RESET CONTACT FORM</h4>
        This will <strong>delete all your achievements</strong>!<br>
        <form action="resetdb.php" method="post">
        your name:
        <input type="text" name="username" value="<?php echo $_SESSION['user_name']; ?>" disabled><br>
        <input type="hidden" name="doit" value="1">
		<input type="hidden" name="crossposts" value="1">
        <input type="submit" value="RESET SUPPORT CONTACT">
        </form>



        <?php
  }


} else {
        echo "You are not authorized!";
}
echo '<br><br><a href="overview.php">back to overview</a>';
?>


