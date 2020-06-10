<?php
// TODO: Add check if admin or not
// best practice would be a separate function
$value = true;

if ($value) {
    phpinfo();
} else {
    header("location: index.php");
}
