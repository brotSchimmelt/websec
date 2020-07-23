<?php

function slug($z)
{
    $z = strtolower($z);
    $z = preg_replace('/[^a-z0-9 -]+/', '', $z);
    $z = str_replace(' ', '-', $z);
    return trim($z, '-');
}

function set_fake_cookie($username)
{
    $cookieName = "SessionCookieID";
    try {
        $sql = "SELECT `xss_fake_cookie_id` FROM users WHERE `user_name`=:user_name";
        $stmt = get_login_db()->prepare($sql);
        $stmt->execute(['user_name' => $username]);

        $result = $stmt->fetch();
        $fakeID = $result['xss_fake_cookie_id'];

        setcookie($cookieName, $fakeID);
    } catch (Exception $e) {
        echo "error: Sorry, it seems like we encountered a problem while setting your cookies. Please report this error.";
    }
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
                    echo '<div class="con-center con-search">';
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

function show_xss_comments()
{
    include(INCL . "comments.php");
}


function add_comment_to_db($comment, $author)
{
    $sql = "INSERT INTO `xss_comments` (`comment_id`, `author`, `text`, `rating`, `timestamp`) VALUES (NULL, :author, :comment, :rating, :timestamp)";
    $stmt = get_shop_db()->prepare($sql);
    $stmt->execute([
        'author' => $author,
        'comment' => $comment,
        'rating' => 5,
        'timestamp' => date("Y-m-d H:i:s")
    ]);
}


function process_csrf($userName, $userPost)
{
    $referrer = $_SERVER['HTTP_REFERER'];
    $pos1 = strpos($referrer, "product.php");
    $pos2 = strpos($referrer, "overview.php");
    $pos3 = strpos($referrer, "friends.php");
    if ($pos1 != false || $pos2 != false || $pos3 != false) {

        if ($userName == $_SESSION['userName']) {

            $sql = "SELECT `user_name` FROM `csrf_posts` WHERE `user_name` = :user_name";
            $stmt = get_shop_db()->prepare($sql);
            $stmt->execute(['user_name' => $userName]);
            $numOfResults = $stmt->rowCount();



            if ($numOfResults < 1) {

                $pwnedSent = ($userPost == "pwned") ? true : false;

                $sql = "INSERT INTO `csrf_posts` (`post_id`,`user_name`,`message`,`referrer`,`timestamp`) VALUES (NULL, :user_name, :message, :referrer, :timestamp)";
                $stmt = get_shop_db()->prepare($sql);
                $stmt->execute([
                    'user_name' => $userName,
                    'message' => $userPost,
                    'referrer' => $referrer,
                    'timestamp' => date("Y-m-d H:i:s")
                ]);
                echo '<h4>Thank You!</h4>We have received your request and will come back to you very soon.<br>Very soon! Really!<br>One day..<br>or never.';

                if (!$pwnedSent) {
                    echo "message: you should have sent 'pwned' but ok. Challenge passed!";
                } else {
                    echo "message: Challenge passed!";
                }
            } else {
                echo "message: You have already posted a request.";
            }
        } else {
            // wrong user
            echo 'error: user mismatch';
        }
    } else {
        // referrer incorrect
        echo '<h4>Something went wrong!</h4>You tried to contact us but the form is disabled.<br>Sorry, you will have to find another way..<br>Do not manipulate the disabled form!';
    }
}



function reset_reflective_xss_db($username)
{
    $newFakeCookieID = bin2hex(openssl_random_pseudo_bytes(16));

    if ($newFakeCookieID) {
        try {
            $sql = "UPDATE `users` SET `xss_fake_cookie_id`=:new_cookie WHERE `user_name` = :user_name";
            get_login_db()->prepare($sql)->execute([
                'new_cookie' => $newFakeCookieID,
                'user_name' => $username
            ]);
            echo "success: The database for the reflective XSS challenge was successfully reset.";
        } catch (Exception $e) {
            echo "error: Sorry, the database could not be reset. Please report this error!";
        }
    } else {
        echo "error: Sorry, it seems like the internal function for cookie creation is broken. Please report this error.";
    }
}

function reset_stored_xss_db($username)
{
    $sql = "DELETE FROM `xss_comments` WHERE `author`= :user_name";
    try {
        get_shop_db()->prepare($sql)->execute(['user_name' => $username]);
        echo "success: The database for the stored XSS challenge was successfully reset.";
    } catch (Exception $e) {
        echo "error: Sorry, your XSS comment database could not be reset. Please report this error.";
    }
}

function reset_sqli_db($username)
{
    try {
        $mail = $_SESSION['userMail'];
        create_sqli_db($username, $mail);
        echo "The SQL injection database was successfully reset.";
    } catch (Exception $e) {
        echo "error: Seems like it went something wrong while we tried to reset your SQL injection database. Please report this error.";
    }
}

function reset_csrf_db($username)
{
    $sql = "DELETE FROM `csrf_posts` WHERE `user_name` = :user_name";
    try {
        get_shop_db()->prepare($sql)->execute(['user_name' => $username]);
        echo "success: The database for the CSRF challenge was successfully reset.";
    } catch (Exception $e) {
        echo "error: Sorry, your CSRF database could not be reset. Please report this error.";
    }
}


function check_xss_challenge($username)
{
    $challengeStatus = false;
    $fakeID = 'youShouldNotGetThisCookiePleaseReportInLearnweb';

    $sql = "SELECT `xss_fake_cookie_id` FROM users WHERE user_name = :user_name";
    try {
        $stmt = get_login_db()->prepare($sql);
        $stmt->execute(['user_name' => $username]);

        if (!$result = $stmt->fetch()) {
            echo "error: User in XSS challenge was not found. Please report this error.";
            return $challengeStatus;
        } else {
            $fakeID = $result['xss_fake_cookie_id'];
        }
    } catch (Exception $e) {
        echo "error: Sorry, your status regarding the XSS challenge could not be fetch from the user database. Please report this error.";
        return $challengeStatus;
    }

    $sql = "SELECT `text` FROM `xss_comments` WHERE `author` = :user_name";
    try {
        $stmt = get_shop_db()->prepare($sql);
        $stmt->execute(['user_name' => $username]);
        while ($row = $stmt->fetch()) {
            $haystack = htmlentities($row['text']);
            if (preg_match("/alert\(.*$fakeID.*\)/i", $haystack)) {
                $challengeStatus = true;
            }
        }
    } catch (Exception $e) {
        echo "error: Sorry, your status regarding the XSS challenge could not be fetch from the challenge database. Please report this error.";
        return $challengeStatus;
    }

    return $challengeStatus;
}

function check_sqli_challenge($username)
{

    $challengeStatus = false;
    $pathToSQLiDB = DAT . $username . ".sqlite";

    $database = new SQLite3($pathToSQLiDB);
    if ($database) {

        $sql = 'SELECT * FROM users WHERE role="admin" and username != "admin";';
        $result = $database->query($sql);
        $row = $result->fetchArray();
        if ($row) {
            $challengeStatus = True;
        }
    } else {
        echo "error: Your SQLi database could not be found. Please reset your database and report this error.";
        return $challengeStatus;
    }

    return $challengeStatus;
}

function check_crosspost_challenge($username)
{

    $challengeStatus = false;

    try {
        $sql = "SELECT `message` FROM `csrf_posts` WHERE `user_name` = :user_name";
        $stmt = get_shop_db()->prepare($sql);
        $stmt->execute(['user_name' => $username]);

        if ($result = $stmt->fetchColumn()) {
            $challengeStatus = true;
        }
        return $challengeStatus;
    } catch (Exception $e) {
        echo "error: Sorry, we could not establish a connection to the CSRF database. Please report this error.";
        return $challengeStatus;
    }
    return $challengeStatus;
}

function check_crosspost_challenge_double($username)
{
    $challengeStatus = false;

    try {
        $sql = "SELECT `referrer` FROM `csrf_posts` WHERE `user_name` = :user_name";
        $stmt = get_shop_db()->prepare($sql);
        $stmt->execute(['user_name' => $username]);

        $needle1 = "product.php";
        $needle2 = "overview.php";
        $needle3 = "friends.php";

        while ($row = $stmt->fetch()) {

            $haystack = $row['referrer'];
            $pos1 = strpos($haystack, $needle1);
            $pos2 = strpos($haystack, $needle2);
            $pos3 = strpos($haystack, $needle3);

            if ($pos1 !== false || $pos2 !== false || $pos3 !== false) {
                $challengeStatus = true;
                return $challengeStatus;
            }
        }
    } catch (Exception $e) {
        echo "error: Sorry, we could not establish a connection to the CSRF database. Please report this error.";
        return $challengeStatus;
    }
    return $challengeStatus;
}
