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

// create SQLite database for the SQLi challenge 
function create_sqli_db($username, $mail)
{

    $dbName = DAT . slug($username) . ".sqlite";

    if (file_exists($dbName)) {
        unlink($dbName);
    }

    $database = new SQLite3($dbName);
    if ($database) {

        // fake password hash that is shown to the user in SQLi challenge
        $fakePwdHash = str_shuffle("superSecureFakePasswordHash13579");

        // add users to the SQLi database
        try {
            $database->exec('CREATE TABLE users (username text NOT NULL, '
                . 'password text, email text, wishlist text, user_status '
                . 'text NOT NULL);');

            $database->exec("INSERT INTO users (username,password,email,"
                . "wishlist, user_status) VALUES ('admin','admin',"
                . "'admin@admin.admin', 'new Mug', 'standard');");

            $database->exec("INSERT INTO users (username,password,email,"
                . "wishlist, user_status) VALUES ('elliot','toor', "
                . "'alderson@allsafe.con', 'Banana Slicer', 'standard');");

            $database->exec("INSERT INTO users (username,password,email,"
                . "wishlist, user_status) VALUES ('l337_h4ck3r','password123',"
                . "'girly95@hotmail.con', 'T-Shirt', 'premium');");

            $database->exec("INSERT INTO users (username,password,email,"
                . "wishlist, user_status) VALUES ('" . $username . "','"
                . $fakePwdHash . "','" . $mail . "', 'empty','standard');");
        } catch (Exception $e) {
            display_exception_msg($e, "053");
        }
    } else {
        throw new Exception("SQLite database could not be created.");
    }
}

// query the SQLite database
function query_sqli_db()
{
    // get search term and user database
    $searchTerm = $_POST['sqli'];
    $userDbPath = DAT . $_SESSION['userName'] . ".sqlite";

    // queries
    $countUserQuery = "SELECT COUNT(*) FROM `users`;";
    $countPremiumQuery = "SELECT COUNT(*) FROM `users` WHERE user_status='premium';";
    $searchQuery = 'SELECT username,email,wishlist FROM users WHERE '
        . 'username="' . $searchTerm . '";';

    // connect to database
    $database = new SQLite3($userDbPath);
    if ($database) {

        // count combined and premium users before search query execution
        $numOfUsersBefore = $database->querySingle($countUserQuery);
        $numOfPremiumBefore = $database->querySingle($countPremiumQuery);

        // split all entered queries in array
        $queries = explode(';', $searchQuery);

        foreach ($queries as $q) {

            // allowed SQL
            $pos1 = strpos($q, "SELECT");
            $pos2 = strpos($q, "INSERT");
            $pos3 = strpos($q, "UPDATE");

            if ($pos1 === false && $pos2 === false && $pos3 === false) {

                // skip any query with unhallowed or without SQL 
                continue;
            } else if ($pos2 !== false || $pos3 !== false) {

                // execute data manipulating query
                try {
                    $database->query($q);
                } catch (Exception $e) {
                    display_exception_msg($e, "055");
                    exit();
                }
            } else {

                // execute SELECT query
                try {
                    $result = $database->query($q);
                } catch (Exception $e) {
                    display_exception_msg($e, "056");
                    exit();
                }
                while ($row = $result->fetchArray()) {
                    echo '<div class="con-center con-search">';
                    echo '<h4 class="display-5">Looks like we found your '
                        . 'friend!</h4><br>';
                    echo "Here are his/her contact infos and wishlist items!<br>";

                    // iterate SELECT results
                    foreach ($row as $key => $value) {
                        if (is_numeric($key)) {

                            // skip IDs
                            continue;
                        }

                        // output results abstracted from the original search query
                        // so SELECT * (...) gives all attributes back
                        if (
                            $key == "username"
                            || $key == "email"
                            || $key == "password"
                            || $key == "wishlist"
                            || $key == "user_status"
                        ) {
                            echo "$key = $value <br>";
                        }
                    }
                    echo "</div>";
                    echo "<br><hr><br>";
                }
            }
        }
        // count new premium users
        if ($database->querySingle($countPremiumQuery) > $numOfPremiumBefore) {

            try {
                $challengeStatus = check_sqli_challenge($_SESSION['userName']);
            } catch (Exception $e) {
                display_exception_msg($e, "058");
                exit();
            }
            // check if user is premium
            if ($challengeStatus) {

                // set challenge to solved in database
                set_challenge_status("sqli", $_SESSION['userName']);

                // code for showing challenge success modal
                return 0;
            }

            // code for showing info modal
            return 1;
        } else if ($database->querySingle($countUserQuery) > $numOfUsersBefore) {

            // code for showing info modal
            return 2;
        }
    } else {
        $msg = "You seem to have an error in your SQL query: "
            . htmlentities($searchTerm);

        throw new Exception($msg);

        // return code for unexpected behaviour
        return -1;
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

    // old: updated with JS implementation
    // filter user comment and check if correct script attack is used
    // $filteredComment = filter_comment($comment);

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

// process the user post for the CSRF challenge
function process_csrf($username, $userPost)
{
    $referrer = $_SERVER['HTTP_REFERER'];

    // pages with open text forms
    $pos1 = strpos($referrer, "product.php");
    $pos2 = strpos($referrer, "overview.php");
    $pos3 = strpos($referrer, "friends.php");

    if ($username == $_SESSION['userName']) {

        // check matching entries in the database
        $SelectSql = "SELECT `user_name` FROM `csrf_posts` WHERE `user_name` = "
            . ":user_name";
        try {
            $stmt = get_shop_db()->prepare($SelectSql);
            $stmt->execute(['user_name' => $username]);
            $numOfResults = $stmt->rowCount();
        } catch (PDOException $e) {
            display_exception_msg($e, "166");
            exit();
        }

        // check if user already made a post
        if ($numOfResults < 1) {

            // check if the right message was used
            $pwnedSent = ($userPost == "pwned") ? true : false;

            // insert user post to database
            $InsertSql = "INSERT INTO `csrf_posts` (`post_id`,`user_name`,"
                . "`message`,`referrer`,`timestamp`) VALUES (NULL, "
                . ":user_name, :message, :referrer, :timestamp)";

            try {
                $stmt = get_shop_db()->prepare($InsertSql);
                $stmt->execute([
                    'user_name' => $username,
                    'message' => $userPost,
                    'referrer' => $referrer,
                    'timestamp' => date("Y-m-d H:i:s")
                ]);
            } catch (PDOException $e) {
                display_exception_msg($e, "167");
                exit();
            }

            // mark contact form as used
            $_SESSION['contactUsed'] = true;

            // set challenge to 'solved'
            if ($pos1 !== false || $pos2 !== false || $pos3 !== false) {
                set_challenge_status("csrf", $username);
                set_challenge_status("csrf_referrer", $username);
            } else {
                // wrong referrer; still passed
                set_challenge_status("csrf", $username);
                return 4;
            }

            if (!$pwnedSent) {
                // wrong message; still passed
                return 1;
            } else {
                // challenge passed
                return 0;
            }
        } else {
            // already a post in the database
            return 3;
        }
    } else {
        // wrong user
        return 2;
    }
}

// Reset XSS challenge
function reset_reflective_xss_db($username)
{

    // unset all challenge cookies
    delete_all_challenge_cookies();

    // generate new value for reflective XSS challenge cookie
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

    // update reflective XSS cookie in Session
    $_SESSION['reflectiveXSS'] = $newChallengeCookie;

    // set new cookie
    setcookie("XSS_YOUR_SESSION", $newChallengeCookie, 0, "/");

    // unset challenge progress in database
    set_challenge_status("reflective_xss", $username, $status = 0);

    // show success modal
    return true;
}

// Reset stored XSS challenge
function reset_stored_xss_db($username)
{

    // delete all challenge cookies and set unrelated cookie again
    delete_all_challenge_cookies();
    setcookie("XSS_YOUR_SESSION", $_SESSION['reflectiveXSS'], 0, "/");

    // generate new value for stored XSS challenge cookie
    try {
        $newChallengeCookie = get_random_token(16);
    } catch (Exception $e) {
        display_exception_msg($e);
        exit();
    }

    // set new cookie in the database
    $sqlCookie = "UPDATE `fakeCookie` SET `stored_xss`=:new_cookie "
        . "WHERE `user_name` = :user_name";

    try {
        get_login_db()->prepare($sqlCookie)->execute([
            'new_cookie' => $newChallengeCookie,
            'user_name' => $username
        ]);
    } catch (PDOException $e) {
        display_exception_msg($e, "113");
        exit();
    }

    // update stored XSS cookie in Session
    $_SESSION['storedXSS'] = $newChallengeCookie;

    // delete user comments in database
    $sqlComment = "DELETE FROM `xss_comments` WHERE `author`= :user_name";
    try {
        get_shop_db()->prepare($sqlComment)->execute(['user_name' => $username]);
    } catch (PDOException $e) {
        display_exception_msg($e, "114");
        exit();
    }

    // empty the current cart of the user
    empty_cart($username);

    // reset modal flag
    unset($_SESSION['showStoredXSSModal']);

    // unset challenge progress in database
    set_challenge_status("stored_xss", $username, $status = 0);

    // show success modal
    return true;
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

    set_challenge_status("sqli", $username, $status = 0);

    // show success modal
    return true;
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

    // unset session variable
    $_SESSION['contactUsed'] = false;

    // unset challenge status in database
    set_challenge_status("csrf", $username, $status = 0);
    set_challenge_status("csrf_referrer", $username, $status = 0);

    // show success modal
    return true;
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
    return stripos($cookie, $result['reflective_xss']) !== false ? true : false;
}

// check if the SQLi challenge is solved
function check_sqli_challenge($username)
{

    $challengeStatus = false;
    $pathToSQLiDB = DAT . $username . ".sqlite";

    $database = new SQLite3($pathToSQLiDB);
    if ($database) {

        // check if the user account is premium
        $sql = "SELECT COUNT(*) FROM `users` WHERE `user_status`='premium' AND "
            . "`username`='" . $username . "';";
        try {
            $result = $database->querySingle($sql);
        } catch (Exception $e) {
            display_exception_msg($e, "057");
            exit();
        }

        if ($result > 0) {
            $challengeStatus = true;
        }
    } else {
        throw new Exception("SQLi Database for " . $username
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
// function compare_cookies($username)
// {

//     if (isset($_COOKIE['XSS_STOLEN_SESSION'])) {

//         // set flag that the user set the cookie for the stored xss challenge
//         $_SESSION['xssCookieSet'] = 1;

//         // check if right cookie is set
//         if ($_COOKIE['XSS_STOLEN_SESSION'] == $_SESSION['storedXSS']) {

//             if (!isset($_SESSION['showStoredXSSModal'])) {

//                 // set up fake cart
//                 update_cart($username);

//                 // set modal flag to not shown
//                 $_SESSION['showStoredXSSModal'] = 0;
//             }
//         } elseif ($_COOKIE['XSS_STOLEN_SESSION'] == $_SESSION['reflectiveXSS']) {

//             // error: user entered wrong XSS cookie 
//             echo "<script>alert('You should set the XSS_STOLEN_SESSION cookie "
//                 . "to the value you obtained from the comment field. Not the "
//                 . "value from the XSS_YOUR_SESSION cookie. Please try "
//                 . "again.');</script>";
//         } else {

//             // error: user entered completely wrong cookie value
//             echo "<script>alert('Sorry, the cookie you have set does not match "
//                 . "the XSS challenge cookies for your user. Please try again. If "
//                 . "this error persists, please report it in the Learnweb forum "
//                 . "together with the cookie you tried to set and your "
//                 . "method. You can also try and reset this challenge in the menu"
//                 . ".');</script>";
//         }
//         /* 
//         * user set the right value to the wrong cookie
//         * technically this is not the intended solution but it will do the trick
//         */
//     } elseif ($_COOKIE['XSS_YOUR_SESSION'] == $_SESSION['storedXSS']) {

//         if (!isset($_SESSION['showStoredXSSModal'])) {

//             // set up fake cart
//             update_cart($username);

//             // set modal flag to not shown
//             $_SESSION['showStoredXSSModal'] = 0;
//         }
//     } else {

//         // cookie for stored xss challenge is not yet set by user in this session
//         $_SESSION['xssCookieSet'] = 0;
//     }
// }

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

// now replaced with JS implementation
// // check if user comment contains XSS attack
// function filter_comment($comment)
// {
//     if (!empty($comment) && preg_match("/document.cookie/", $comment)) {


//         $cookie = $_SESSION['storedXSS'];

//         return "<script>alert('XSS_STOLEN_SESSION=" . $cookie . "');</script>";
//     } else {

//         return $comment;
//     }
// }


function compare_cookies($username)
{
    // iterate through all cookies
    foreach ($_COOKIE as $cookie => $value) {

        // check if one cookie has the stored XSS challenge cookies value
        $pos1 = stripos($value, $_SESSION['storedXSS']);
        if ($pos1 !== false) {

            // check if 'welcome back, elliot' modal has already been shown
            if (!isset($_SESSION['showStoredXSSModal'])) {

                // set up fake cart
                update_cart($username);
                // set modal flag to not shown
                $_SESSION['showStoredXSSModal'] = 0;
            }
        }
    }
}
