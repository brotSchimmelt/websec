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
// TODO: Delete ? Cookies are now set during login
function set_fake_cookie($username)
{
    $cookieName = "XSS_YOUR_SESSION";

    $sql = "SELECT `reflective_xss` FROM "
        . "fakeCookie WHERE `user_name`=:user_name";

    try {
        $stmt = get_login_db()->prepare($sql);
        $stmt->execute(['user_name' => $username]);
        $result = $stmt->fetch();
        $fakeID = $result['reflective_xss'];
    } catch (PDOException $e) {
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

    // check if already one comment from the current user exists
    check_user_comment_exists($author);

    // filter user comment and check if correct script attack is used
    $filteredComment = filter_comment($comment);

    $sql = "INSERT INTO `xss_comments` (`comment_id`, `author`, `text`, "
        . "`rating`, `timestamp`) VALUES "
        . "(NULL, :author, :comment, :rating, :timestamp)";

    try {
        $stmt = get_shop_db()->prepare($sql);
        $stmt->execute([
            'author' => $author,
            'comment' => $filteredComment,
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

    // unset all challenge cookies
    delete_all_challenge_cookies();

    // generate new value for stored XSS challenge cookie
    try {
        $newChallengeCookie = get_random_token(16);
    } catch (Exception $e) {
        display_exception_msg($e);
        exit();
    }

    // set new cookie in the database
    $sql = "UPDATE `fakeCookie` SET `reflective_xss`=:new_cookie "
        . "WHERE `user_name` = :user_name";

    try {
        get_login_db()->prepare($sql)->execute([
            'new_cookie' => $newChallengeCookie,
            'user_name' => $username
        ]);
    } catch (PDOException $e) {
        display_exception_msg($e, "113");
        exit();
    }

    // update stored XSS cookie in Session
    $_SESSION['storedXSS'] = $newChallengeCookie;

    // set new cookie
    setcookie("XSS_YOUR_SESSION", $newChallengeCookie, 0, "/");

    // unset challenge progress in database
    set_challenge_status("reflective_xss", $username, $status = 0);

    // show success modal
    return true;
}

// Reset stored XSS challenge
// TODO: add new implementation
// unset 1 challenge cookie
// generate 1 new cookie/ token
// update `fakeCookie` with new value
// update new cookie in $_SESSION
// delete comment in Database
// empty cart
// call (new) function unset_challenge_status($challenge, $username)
// create Modal
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
        exit();
    }
    echo "success: The database for the CSRF challenge was successfully reset.";
}

// check if the XSS challenge was solved
function check_reflective_xss_challenge($username, $cookie)
{

    // get cookie from db
    $sql = "SELECT `reflective_xss` FROM `fakeCookie` WHERE `user_name`=?";

    try {
        $stmt = get_login_db()->prepare($sql);
        $stmt->execute([$username]);
        $result = $stmt->fetch();
    } catch (PDOException $e) {
        display_exception_msg($e, "123");
        exit();
    }

    // check if correct cookie is entered
    return strpos($cookie, $result['reflective_xss']) !== false ? true : false;

    // OLD CODE:
    /*
    $challengeStatus = false;
    $fakeID = 'youShouldNotGetThisCookiePleaseReportInLearnweb';

    $sql = "SELECT `reflective_xss` FROM fakeCookie WHERE "
        . "user_name = :user_name";
    try {
        $stmt = get_login_db()->prepare($sql);
        $stmt->execute(['user_name' => $username]);

        if (!$result = $stmt->fetch()) {
            trigger_error("Code Error: No entry found for " . $username
                . " in XSS challenge.");
            return $challengeStatus;
        } else {
            $fakeID = $result['reflective_xss'];
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
    */
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

// set challenge status in the database to solved
function set_challenge_status($challenge, $username, $status = 1)
{
    // filter challenge name since prepared statements do not work for
    // table names etc.
    $challengeField = filter_var($challenge, FILTER_SANITIZE_SPECIAL_CHARS);
    $challengeStatus = filter_var($status, FILTER_SANITIZE_NUMBER_INT);

    // check if challenge status is either 0 or 1
    $challengeStatus = ($challengeStatus != 1) ? 0 : 1;

    $sql = "UPDATE `challengeStatus` SET " . $challengeField . "="
        . $challengeStatus . " WHERE `user_name`=?";

    try {
        $stmt = get_login_db()->prepare($sql);
        $stmt->execute([$username]);
    } catch (PDOException $e) {
        display_exception_msg($e, "124");
        exit();
    }
}

// lookup challenge status in the database
function lookup_challenge_status($challenge, $username)
{
    // filter challenge name since prepared statements do not work for
    // table names etc.
    $challengeField = filter_var($challenge, FILTER_SANITIZE_SPECIAL_CHARS);

    $sql = "SELECT " . $challengeField . " FROM `challengeStatus` WHERE "
        . "`user_name`=?";

    try {
        $stmt = get_login_db()->prepare($sql);
        $stmt->execute([$username]);
        $result = $stmt->fetch();
    } catch (PDOException $e) {
        display_exception_msg($e, "125");
        exit();
    }

    // check if challenge was already solved
    return $result[$challengeField] == 1 ? true : false;
}

// ensure that only one comment per user exists in the database
function check_user_comment_exists($username)
{
    $sql = "SELECT `comment_id` FROM `xss_comments` WHERE `author`=?";

    try {
        $stmt = get_shop_db()->prepare($sql);
        $stmt->execute([$username]);
    } catch (PDOException $e) {
        display_exception_msg($e, "162");
        exit();
    }

    // delete old comment
    if ($stmt->fetch()) {

        $deleteSql = "DELETE FROM `xss_comments` WHERE `author`=?";

        try {
            $stmt = get_shop_db()->prepare($deleteSql);
            $stmt->execute([$username]);
        } catch (PDOException $e) {
            display_exception_msg($e, "163");
            exit();
        }
    }
}

// compare the set cookies with the solution cookies
function compare_cookies($username)
{

    if (isset($_COOKIE['XSS_STOLEN_SESSION'])) {

        // set flag that the user set the cookie for the stored xss challenge
        $_SESSION['xssCookieSet'] = 1;

        // check if right cookie is set
        if ($_COOKIE['XSS_STOLEN_SESSION'] == $_SESSION['storedXSS']) {

            if (!isset($_SESSION['showStoredXSSModal'])) {

                // set up fake cart
                update_cart($username);

                // set modal flag to not shown
                $_SESSION['showStoredXSSModal'] = 0;
            }
        } elseif ($_COOKIE['XSS_STOLEN_SESSION'] == $_SESSION['reflectiveXSS']) {

            // error: user entered wrong XSS cookie 
            echo "<script>alert('You should set the XSS_STOLEN_SESSION cookie "
                . "to the value you obtained from the comment field. Not the "
                . "value from the XSS_YOUR_SESSION cookie. Please try "
                . "again.');</script>";
        } else {

            // error: user entered completely wrong cookie value
            echo "<script>alert('Sorry, the cookie you have set does not match "
                . "the XSS challenge cookies for your user. Please try again. If "
                . "this error persists, please report it in the Learnweb forum "
                . "together with the cookie you tried to set and your "
                . "method. You can also try and reset this challenge in the menu"
                . ".');</script>";
        }
        /* 
        * user set the right value to the wrong cookie
        * technically this is not the intended solution but it will do the trick
        */
    } elseif ($_COOKIE['XSS_YOUR_SESSION'] == $_SESSION['storedXSS']) {

        if (!isset($_SESSION['showStoredXSSModal'])) {

            // set up fake cart
            update_cart($username);

            // set modal flag to not shown
            $_SESSION['showStoredXSSModal'] = 0;
        }
    } else {

        // cookie for stored xss challenge is not yet set by user in this session
        $_SESSION['xssCookieSet'] = 0;
    }
}

// set the users current cart to the 'fake' cart
function update_cart($username)
{

    // empty the current cart of the user
    empty_cart($username);

    $sql = "INSERT INTO `cart` (`position_id`, `prod_id`, `user_name`, "
        . "`quantity`, `timestamp`) VALUES "
        . "(NULL, :prod_id, :user_name, :quantity, :date)";

    // products that are added to the new 'fake' cart
    $productsToAdd = array('1', '3', '4', '5');
    $productQuantity = array('32', '1', '5', '3');

    try {
        for ($i = 0; $i < count($productsToAdd); $i++) {
            $stmt = get_shop_db()->prepare($sql);
            $stmt->execute([
                'prod_id' => $productsToAdd[$i],
                'user_name' => $username,
                'quantity' => $productQuantity[$i],
                'date' => date("Y-m-d H:i:s")
            ]);
        }
    } catch (Exception $e) {
        display_exception_msg($e, "165");
        exit();
    }

    // set fake cart
    $_SESSION['fakeCart'] = true;
}

// check if the stored xss challenge was solved
function check_stored_xss_challenge($username)
{

    $sql = "SELECT `position_id` FROM `cart` WHERE `user_name`=? "
        . "AND `prod_id`=2";

    try {

        $stmt = get_shop_db()->prepare($sql);
        $stmt->execute([$username]);
        $result = $stmt->fetch();
    } catch (PDOException $e) {
        display_exception_msg($e, "");
        exit();
    }

    if ($result) {
        // set challenge to passed
        set_challenge_status("stored_xss", $username);

        // set fake cart flag to false so cart can work normally again
        $_SESSION['fakeCart'] = false;

        // set modal flag to not shown
        $_SESSION['showSuccessModalXSS'] = 0;
    }
}

// check if user comment contains XSS attack
function filter_comment($comment)
{
    if (!empty($comment) && preg_match("/document.cookie/", $comment)) {


        $cookie = $_SESSION['storedXSS'];

        return "<script>alert('XSS_STOLEN_SESSION=" . $cookie . "');</script>";
    } else {

        return $comment;
    }
}
