<?php

function slug($z)
{
    $z = strtolower($z);
    $z = preg_replace('/[^a-z0-9 -]+/', '', $z);
    $z = str_replace(' ', '-', $z);
    return trim($z, '-');
}

function create_sqli_db($username, $mail)
{

    $dbName = DAT . slug($username) . ".sqlite";

    if (file_exists($dbName)) {
        unlink($dbName);
    }

    $database = new SQLite3($dbName);
    if ($database) {

        $fakePwdHash = str_shuffle(str_repeat("superSecureFakePasswordHash13579", 2));

        $database->exec('CREATE TABLE users (username text NOT NULL, password text, email text, role text NOT NULL);');
        $database->exec("INSERT INTO users (username,password,email,role) VALUES ('admin','admin','admin@admin.admin','admin');");
        $database->exec("INSERT INTO users (username,password,email,role) VALUES ('elliot','toor','alderson@allsafe.con','user');");
        $database->exec("INSERT INTO users (username,password,email,role) VALUES ('l337_h4ck3r','password123','girly95@hotmail.con','user');");
        $database->exec("INSERT INTO users (username,password,email,role) VALUES ('" . $username . "','" . $fakePwdHash . "','" . $mail . "','user');");
    } else {
        echo "error: database was not created!";
    }
}

function query_sqli_db()
{
    $searchTerm = $_POST['sqli'];
    $userDbPath = DAT . $_SESSION['userName'] . ".sqlite";

    $countUserQuery = "SELECT COUNT(*) FROM `users`;";
    $countAdminQuery = "SELECT COUNT(*) FROM `users` WHERE role='admin';";
    $searchQuery = 'SELECT username,email FROM users WHERE username="' . $searchTerm . '";';

    $database = new SQLite3($userDbPath);
    if ($database) {

        $numOfUsersBefore = $database->querySingle($countUserQuery);
        $numOfAdminsBefore = $database->querySingle($countAdminQuery);

        $queries = explode(';', $searchQuery);

        foreach ($queries as $q) {

            // Debug: echo "query: " . $q . "<br>";

            $pos1 = strpos($q, "SELECT");
            $pos2 = strpos($q, "INSERT");
            if ($pos1 === false && $pos2 === false) {
                continue;
            } else if ($pos2 !== false) {
                $database->query($q);
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
        if ($database->querySingle($countAdminQuery) > $numOfAdminsBefore) {
            echo "message: Great! You added a new admin user and completed the challenge!";
        } else if ($database->querySingle($countUserQuery) > $numOfUsersBefore) {
            echo "message: Seems like you successfully added a new user to the database! Now try to insert a user with the role <strong>admin</strong>.";
        }
    } else {
        echo 'You seem to have an error in your SQL query: ' . htmlentities($searchTerm);
    }
}
