<?php
    function isUserUnlocked(){
        $unlocked = "-1";
        $mysqli = new mysqli("localhost", "phplogin", "6Zhn5Tgb", "phplogin");
        if (mysqli_connect_errno()) {
                printf("Connect failed: %s\n", mysqli_connect_error());
                exit();
        }
        $value = $_SESSION['user_name'];
        $query = "SELECT unlocked FROM users WHERE user_name = '".$value."'";
        if ($result = $mysqli->query($query)) {
                while ($row = $result->fetch_assoc()) {
                        $unlocked = $row["unlocked"];
                }
                $result->close();
        }
        $mysqli->close();
        return $unlocked;
    }

    function isUserAdmin(){
        $isadmin = "-1";
        $mysqli = new mysqli("localhost", "phplogin", "6Zhn5Tgb", "phplogin");
        if (mysqli_connect_errno()) {
                printf("Connect failed: %s\n", mysqli_connect_error());
                exit();
        }
        $value = $_SESSION['user_name'];
        $query = "SELECT isadmin FROM users WHERE user_name = '".$value."'";
        if ($result = $mysqli->query($query)) {
                while ($row = $result->fetch_assoc()) {
                        $isadmin = $row["isadmin"];
                }
                $result->close();
        }
        $mysqli->close();
        return $isadmin;
    }

    function unlockuser(){
	$success = "-1";
        $mysqli = new mysqli("localhost", "phplogin", "6Zhn5Tgb", "phplogin");
        if (mysqli_connect_errno()) {
                printf("Connect failed: %s\n", mysqli_connect_error());
                exit();
        }
        $value = $_SESSION['user_name'];
        $query = "UPDATE `users` SET `unlocked`='1' WHERE user_name = '".$value."'";
	if ($mysqli->query($query) === TRUE) {
		$success = "1";
	} else {
		echo "Error updating record: " . $mysqli->error;
		$success = "0";
	}
        $mysqli->close();
        return $success;
    }

    function lockuser(){
        $success = "-1";
        $mysqli = new mysqli("localhost", "phplogin", "6Zhn5Tgb", "phplogin");
        if (mysqli_connect_errno()) {
                printf("Connect failed: %s\n", mysqli_connect_error());
                exit();
        }
        $value = $_SESSION['user_name'];
        $query = "UPDATE `users` SET `unlocked`='0' WHERE user_name = '".$value."'";
        if ($mysqli->query($query) === TRUE) {
                $success = "1";
        } else {
                echo "Error updating record: " . $mysqli->error;
                $success = "0";
        }
        $mysqli->close();
        return $success;
    }

    function isregistrationenabled(){
	$enabled = "-1";
	if (file_exists('disableregistration')) {
		$enabled = '0';
	} else {
		$enabled = '1';
	}
	return $enabled;
    }

    function isloginenabled(){
        $enabled = "-1";
        if (file_exists('disablelogin')) {
                $enabled = '0';
        } else {
                $enabled = '1';
        }
        return $enabled;
    }

    function slug($z){
	$z = strtolower($z);
	$z = preg_replace('/[^a-z0-9 -]+/', '', $z);
	$z = str_replace(' ', '-', $z);
	return trim($z, '-');
    }

?>
