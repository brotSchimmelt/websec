<?php

function slug($z)
{
    $z = strtolower($z);
    $z = preg_replace('/[^a-z0-9 -]+/', '', $z);
    $z = str_replace(' ', '-', $z);
    return trim($z, '-');
}

function create_sqli_db($username)
{

    $dbName = DAT . slug($username) . ".sqlite";

    if (file_exists($dbName)) {
        unlink($dbName);
    }

    $database = new SQLite3($dbName);
    if ($database) {
        $thisUsername = "-1";
        $thisUserPwd = "-1";
        $thisUserMail = "-1";

        # unnecessary since the challenge does not involve the own user credentials
        // $sql = "SELECT `user_name`,`user_pwd_hash`,`user_wwu_email` FROM `users` WHERE user_name = :user_name";
        // $stmt = get_login_db()->prepare($sql);
        // $stmt->execute(['user_name' => $_SESSION['userName']]);
        // $result = $stmt->fetch();

        $database->exec('CREATE TABLE users (username text NOT NULL, password text, email text, role text NOT NULL);');
        $database->exec("INSERT INTO users (username,password,email,role) VALUES ('admin','admin','admin@admin.admin','admin');");
        $database->exec("INSERT INTO users (username,password,email,role) VALUES ('elliot','toor','alderson@f.society','user');");
        $database->exec("INSERT INTO users (username,password,email,role) VALUES ('gandalf','password123','girly95@hotmail.con','user');");
    } else {
        echo "error: database was not created!";
    }
}
