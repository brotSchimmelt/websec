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
        $database->exec("INSERT INTO users (username,password,email,role) VALUES ('l337_h4ck3r','password123','girly95@hotmail.con','user');");
        $database->exec("INSERT INTO users (username,password,email,role) VALUES ('user','user','user@user.user','user');");
    } else {
        echo "error: database was not created!";
    }
}

function query_sqli_db()
{
    $searchTerm = $_GET['sqli'];
    $userDbPath = DAT . $_SESSION['userName'] . ".sqlite";

    $database = new SQLite3($userDbPath);
    if ($database) {
        $sql = 'SELECT username,email FROM users WHERE username="' . $searchTerm . '";';

        $queries = explode(';', $sql);

        foreach ($queries as $q) {
            $pos1 = strpos($q, "SELECT");
            $pos2 = strpos($q, "INSERT");
            if ($pos1 === false && $pos2 === false) {
                continue;
            } else {
                $result = $database->query($q);
                while ($row = $result->fetchArray()) {
                    echo '<div class="page-center prod-center">';
                    echo '<h4 class="display-5">Looks like we found your friend!</h4><br>';
                    echo "Here are his/her contact infos!<br>";

                    foreach ($row as $key => $value) {
                        if (is_numeric($key)) {
                            continue;
                        }
                        if ($key == "username" || $key == "email" || $key == "password" || $key == "role") {
                            echo "$key = $value <br>";
                        }
                    }
                    echo "</div>";
                    echo "<br><hr><br>";
                }
            }
        }
    } else {
        echo 'You seem to have an error in your SQL query: ' . htmlentities($searchTerm);
    }
}
