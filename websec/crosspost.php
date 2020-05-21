<?php require_once("libraries/password_compatibility_library.php"); require_once("config/db.php"); require_once("classes/Login.php"); require_once("functions.php"); include 
'header.php'; $login = new Login(); if ($login->isUserLoggedIn() == true && isUserUnlocked()) {
	echo 'Hey ' . $_SESSION['user_name'] . ',<br><br>';
	if(isset($_POST['uname']) && isset($_POST['upost'])) {
		$referrer = $_SERVER['HTTP_REFERER'];
		$pos1 = strpos($referrer,"simplexss.php");
		$pos2 = strpos($referrer,"storedxss.php");
		if ($pos1 != false || $pos2 != false ){
			# request comes from one of the xss sites
			$uname = $_POST['uname'];
			$upost = $_POST['upost'];
			if($uname == $_SESSION['user_name']){
				$mysqli = new mysqli("localhost", "websec", "S0s3zwo9z3hn", "websec");
			        if (mysqli_connect_errno()) {
                		        printf("Connect failed: %s\n", mysqli_connect_error());
                        		exit();
			        }
			        $query = "SELECT username FROM crossposts WHERE username = '".$uname."';";
				if ($result = $mysqli->query($query)) {
					if ($result->num_rows < 1){
						echo($timestamp);
						$timestamp = date("Ymd-His");
						#$mysqli = new mysqli("127.0.0.1", "websec", "S0s3zwo9z3hn", "websec"); if ($mysqli->connect_errno) {
						#	echo "Failed to connect to MySQL: (" . $mysqli->connect_errno . ") " . $mysqli->connect_error;
						#}
						if (!($stmt = $mysqli->prepare("INSERT INTO crossposts (username,post,timestamp,referrer) VALUES (?,?,?,?)"))) {
							echo "error (bkwm): (" . $mysqli->errno . ") " . $mysqli->error;
						}
						if (!$stmt->bind_param("ssss", $uname, $upost, $timestamp, $referrer)) {
							echo "error (yqwh): (" . $stmt->errno . ") " . $stmt->error;
						}
						if (!$stmt->execute()) {
							echo "error (bhwn): (" . $stmt->errno . ") " . $stmt->error;
						}
						$stmt->close();
						echo '<h4>Thank You!</h4>We have received your request and will come back to you very soon.<br>Very soon! Really!<br>One day..<br>or never.';
					} else {
						echo 'you have already posted a request!';
					}
			} else {
				echo 'error (qlem)';
			}
			$mysqli->close();
		} else {
			echo 'error: user mismatch';
		}
	  } else {
		  # referrer incorrect
		  echo '<h4>Something went wrong!</h4>You tried to contact us but the form is disabled.<br>Sorry, you will have to find another way..<br>Do not manipulate the disabled form!';
	  }
	}
	?>
	<h2>Contact Our Support Team</h2>
	We are here for you every day, twentyfour hours a day, 365 days a year!
	<h4>Contact Form</h4>
	Dear customer,<br>
	our contact form has been temporarily disabled.<br>We were experiencing heavy hacker attacks at our website and decided<br>to shut down our services for a few days/weeks/months.<br>
	In urgent cases please contact our support team.<br>
	Thanks!<br>
	<br>
	<form action="thisfile" method="post" id="reviewform">
	your name:
	<input type="text" name="username" value="<?php echo $_SESSION['user_name']; ?>" disabled><br>
	<input type="hidden" name="uname" value="<?php echo $_SESSION['user_name']; ?>">
	your message for us:
	<input type="text" name="upost" size="30" disabled><br><br>
	<input type="submit" value="Submit" disabled>
	</form>
	<?php
} else {
        echo "You are not authozired!";
}
echo '<br><br><br><a href="overview.php">back to overview</a>'; ?>
