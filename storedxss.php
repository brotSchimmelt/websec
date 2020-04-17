<?php
require_once("libraries/password_compatibility_library.php");
require_once("config/db.php");
require_once("classes/Login.php");
require_once("functions.php");
include 'header.php';
$login = new Login();

if ($login->isUserLoggedIn() == true && isUserUnlocked()) {
        #echo 'Hey ' . $_SESSION['user_name'] . ',<br><br>';
	if(isset($_POST['uname']) && isset($_POST['ucomment'])) {
		$uname = $_POST['uname'];
		$ucomment = $_POST['ucomment'];
		if($uname == $_SESSION['user_name']){
			$timestamp = date("Ymd-His");
			$mysqli = new mysqli("127.0.0.1", "websec", "S0s3zwo9z3hn", "websec");
			if ($mysqli->connect_errno) {
				echo "Failed to connect to MySQL: (" . $mysqli->connect_errno . ") " . $mysqli->connect_error;
			}
                        $query = "SELECT username FROM xsscomments WHERE username = '".$uname."';";
                        if ($result = $mysqli->query($query)) {
                                if ($result->num_rows < 5){
					if (!($stmt = $mysqli->prepare("INSERT INTO xsscomments (username,comment_id,comment) VALUES (?,?,?)"))) {
						echo "error (akwm): (" . $mysqli->errno . ") " . $mysqli->error;
					}
					if (!$stmt->bind_param("sss", $uname, $timestamp, $ucomment)) {
						echo "error (zqwh): (" . $stmt->errno . ") " . $stmt->error;
					}
					if (!$stmt->execute()) {
						echo "error (ahwn): (" . $stmt->errno . ") " . $stmt->error;
					}
					$stmt->close();
				} else {
					echo '<h4>You cannot post more than 5 reviews!</h4><hr>';
				}
			}
		}
	}
	?>

	<h2>The Greates Banana Slicer of All Time</h2>

	<h4>Product Description</h4>
	<table border="0" width="500" cellpadding="5">
	<tr><td>
	Here it comes: The world's most famous banana slicer!<br><br>
	You will never need any other banana slicer once you have one of these. It is amazing! Consisting of the findest plastic pieces and absolutely non-sharp, child-proof, never-cutting razors, it does exactly what you want it for.<br><br>
	<button type="button"><strong>BUY NOW</strong></button><br>
	<font size="small">only 5.879 in stock</font><br><br><br>
	</td><td><img src="bananaslicer.jpg" width="250"></td></tr>
	</table>

	<h4>Write a Review</h4>
	<form action="storedxss.php" method="post" id="reviewform">
	your name:
	<input type="text" name="username" value="<?php echo $_SESSION['user_name']; ?>" disabled><br>
	<input type="hidden" name="uname" value="<?php echo $_SESSION['user_name']; ?>">
	your comment:<br>
	<input type="text" name="ucomment" size="50"><br>
	<input type="submit" value="Submit">
	</form>

	<br>
	<h4>User Reviews</h4>
	<?php
	$mysqli = new mysqli("localhost", "websec", "S0s3zwo9z3hn", "websec");
	if (mysqli_connect_errno()) {
			printf("Connect failed: %s\n", mysqli_connect_error());
			exit();
	}
	$value = $_SESSION['user_name'];
	$query = "SELECT id,username,comment FROM xsscomments WHERE username = '".$value."' OR username = 'Reviewer' ORDER BY id DESC;";
	if ($result = $mysqli->query($query)) {
			?>
			<table border="1" width="500" cellpadding="10">
			<tr><td><em>Author</em></td><td><em>Review</td></tr>
			<?php
			while ($row = $result->fetch_assoc()) {
				echo '<tr><td>'.$row['username'].'</td><td>'.$row['comment'].'</td></tr>';
			}
			?>
			</table><br>
			<?php
			$result->close();
	}
	$mysqli->close();

} else {
        echo "You are not authozired!";
}

echo '<br><br><br><a href="overview.php">back to overview</a>';

?>
