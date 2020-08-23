<?php
// TODO: Add correct error handling

// trim string to include only valid characters
function slug($z)
{
    $z = strtolower($z);
    $z = preg_replace('/[^a-z0-9 -]+/', '', $z);
    $z = str_replace(' ', '-', $z);
    return trim($z, '-');
}

// set challenge cookie for user
function set_fake_cookie($username)
{
    $cookieName = "SessionCookieID";

    $sql = "SELECT `xss_fake_cookie_id` FROM "
        . "users WHERE `user_name`=:user_name";

    try {
        $stmt = get_login_db()->prepare($sql);
        $stmt->execute(['user_name' => $username]);
        $result = $stmt->fetch();
        $fakeID = $result['xss_fake_cookie_id'];
    } catch (PDOException $e) {
        $msg = "The cookie for the XSS challenge could not be set.";
        display_exception_msg($e, "112");
        exit();
    }
    setcookie($cookieName, $fakeID);
}

// create SQLite database for the SQLi challenge 
function create_sqli_db($username, $mail)
{

    $dbName = DAT . slug($username) . ".sqlite";

    if (file_exists($dbName)) {
        unlink($dbName);
    }

    $database = new SQLite3($dbName);
    if ($database) {

        $fakePwdHash = str_shuffle(str_repeat("superSecureFakePasswordHash13579", 2));

        $database->exec('CREATE TABLE users (username text NOT NULL, 
        password text, email text, role text NOT NULL);');

        $database->exec("INSERT INTO users (username,password,email,role) 
        VALUES ('admin','admin','admin@admin.admin','admin');");

        $database->exec("INSERT INTO users (username,password,email,role) 
        VALUES ('elliot','toor','alderson@allsafe.con','user');");

        $database->exec("INSERT INTO users (username,password,email,role) 
        VALUES ('l337_h4ck3r','password123','girly95@hotmail.con','user');");

        $database->exec("INSERT INTO users (username,password,email,role) 
        VALUES ('" . $username . "','" . $fakePwdHash . "','" . $mail . "','user');");
    } else {
        throw new Exception("SQLite database could not be created.");
    }
}

// SQLi challenge
// TODO: Add premium user instead of admin
// TODO: Add exception and error handling
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

// display the product comments
function show_xss_comments()
{
    include(INCL . "comments.php");
}

// add product comment
function add_comment_to_db($comment, $author)
{
    $sql = "INSERT INTO `xss_comments` (`comment_id`, `author`, `text`, "
        . "`rating`, `timestamp`) VALUES "
        . "(NULL, :author, :comment, :rating, :timestamp)";

    try {
        $stmt = get_shop_db()->prepare($sql);
        $stmt->execute([
            'author' => $author,
            'comment' => $comment,
            'rating' => 5,
            'timestamp' => date("Y-m-d H:i:s")
        ]);
    } catch (PDOException $e) {
        display_exception_msg($e, "161");
        exit();
    }
}

// CSRF Challenge
// TODO: Add error & exception handling
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

// Reset XSS challenge
function reset_reflective_xss_db($username)
{
    include_once(FUNC_LOGIN);

    try {
        $newFakeCookieID = get_random_token(16);
    } catch (Exception $e) {
        display_exception_msg($e);
        exit();
    }

    $sql = "UPDATE `users` SET `xss_fake_cookie_id`=:new_cookie "
        . "WHERE `user_name` = :user_name";

    try {
        get_login_db()->prepare($sql)->execute([
            'new_cookie' => $newFakeCookieID,
            'user_name' => $username
        ]);
    } catch (PDOException $e) {
        display_exception_msg($e, "113");
        exit();
    }

    echo "success: The database for the reflective XSS challenge "
        . "was successfully reset.";
}

// Reset stored XSS challenge
function reset_stored_xss_db($username)
{
    $sql = "DELETE FROM `xss_comments` WHERE `author`= :user_name";
    try {
        get_shop_db()->prepare($sql)->execute(['user_name' => $username]);
    } catch (PDOException $e) {
        display_exception_msg($e, "114");
        exit();
    }

    echo "success: The database for the stored XSS challenge was "
        . "successfully reset.";
}

// Reset SQLi challenge
function reset_sqli_db($username)
{
    $mail = $_SESSION['userMail'];

    try {
        create_sqli_db($username, $mail);
    } catch (Exception $e) {
        display_exception_msg($e, "052");
        exit();
    }
    echo "The SQL injection database was successfully reset.";
}

// reset CSRF challenge
function reset_csrf_db($username)
{
    $sql = "DELETE FROM `csrf_posts` WHERE `user_name` = :user_name";

    try {
        get_shop_db()->prepare($sql)->execute(['user_name' => $username]);
    } catch (PDOException $e) {
        display_exception_msg($e, "114");
    }
    echo "success: The database for the CSRF challenge was successfully reset.";
}

// check if the XSS challenge was solved
function check_xss_challenge($username)
{
    $challengeStatus = false;
    $fakeID = 'youShouldNotGetThisCookiePleaseReportInLearnweb';

    $sql = "SELECT `xss_fake_cookie_id` FROM users WHERE user_name = :user_name";
    try {
        $stmt = get_login_db()->prepare($sql);
        $stmt->execute(['user_name' => $username]);

        if (!$result = $stmt->fetch()) {
            trigger_error("Code Error: No entry found for " . $username
                . " in XSS challenge.");
            return $challengeStatus;
        } else {
            $fakeID = $result['xss_fake_cookie_id'];
        }
    } catch (PDOException $e) {
        display_exception_msg($e, "115");
        exit();
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
    } catch (PDOException $e) {
        display_exception_msg($e, "116");
        exit();
    }

    return $challengeStatus;
}

// check if the SQLi challenge is solved
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
            $challengeStatus = true;
        }
    } else {
        trigger_error("Code Error: SQLi Database for " . $username
            . " was not found.");
        return $challengeStatus;
    }

    return $challengeStatus;
}

// check if CSRF challenge was solved
function check_crosspost_challenge($username)
{

    $challengeStatus = false;

    $sql = "SELECT `message` FROM `csrf_posts` WHERE `user_name` = :user_name";

    try {
        $stmt = get_shop_db()->prepare($sql);
        $stmt->execute(['user_name' => $username]);

        if ($result = $stmt->fetchColumn()) {
            $challengeStatus = true;
        }
    } catch (PDOException $e) {
        display_exception_msg($e, "117");
        exit();
    }
    return $challengeStatus;
}

// check if CSRF challenge was solved with the correct referer
function check_crosspost_challenge_double($username)
{
    $challengeStatus = false;

    $sql = "SELECT `referrer` FROM `csrf_posts` WHERE `user_name` = :user_name";

    try {
        $stmt = get_shop_db()->prepare($sql);
        $stmt->execute(['user_name' => $username]);
    } catch (PDOException $e) {
        display_exception_msg($e, "118");
        exit();
    }

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
    return $challengeStatus;
}
