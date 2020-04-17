<!-- if you need user information, just put them into the $_SESSION variable and output them here -->
Hey, <?php echo $_SESSION['user_name']; ?>. You are logged in.
<a href="index.php?logout">Logout</a>
<br><br>
<a href="overview.php">overview</a>
<script type="text/javascript">window.location.replace("overview.php");</script>
