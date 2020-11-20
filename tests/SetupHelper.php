<?php

namespace test\helper;

final class SetupHelper
{
    /**
     * Insert a user into the 'users' database.
     * 
     * @param string $name Name of the user.
     * @param string $mail User mail address.
     * @param string $hash Password hash.
     * @param int $unlocked Unlocked flag.
     * @param int $admin Admin flag.
     */
    public static function insertUser(
        $name,
        $mail,
        $hash = "hash",
        $unlocked = 0,
        $admin = 0
    ): void {
        $insertUser = "INSERT IGNORE INTO users (user_id, user_name, "
            . "user_wwu_email, user_pwd_hash, is_unlocked, is_admin, "
            . "timestamp, last_login) VALUE (NULL, :user, :mail, :pwd_hash, "
            . ":unlocked, :admin, DEFAULT, DEFAULT)";

        $stmt = get_login_db()->prepare($insertUser);
        $stmt->execute([
            'user' => $name,
            'mail' => $mail,
            'pwd_hash' => $hash,
            'unlocked' => $unlocked,
            'admin' => $admin
        ]);
    }
    /**
     * Insert user in fake cookie table.
     * 
     * @param string $name Name of the user.
     * @param string $reflective_xss Reflective XSS cookie.
     * @param string $stored_xss Stored XSS cookie.
     * @param string $fake_token Fake Cookie for CSRF challenge.
     */
    public static function insertFakeCookie(
        $name,
        $reflective_xss = "reflective_cookie",
        $stored_xss = "stored_cookie",
        $fake_token = "csrf_token"
    ) {
        $sql = "INSERT IGNORE INTO fakeCookie (id, user_name, reflective_xss, "
            . "stored_xss, fake_token) VALUE (NULL,:user,:rxss,:sxss,:csrf)";

        $stmt = get_login_db()->prepare($sql);
        $stmt->execute([
            'user' => $name,
            'rxss' => $reflective_xss,
            'sxss' => $stored_xss,
            'csrf' => $fake_token
        ]);
    }

    /**
     * Set all challenges for a given user.
     * 
     * @param string $name Name of the user.
     * @param int $reflective_xss Challenge status.
     * @param int $stored_xss Challenge status.
     * @param int $sqli Challenge status.
     * @param int $csrf Challenge status.
     */
    public static function insertAllChallenges(
        $name,
        $reflective_xss = 0,
        $stored_xss = 0,
        $sqli = 0,
        $csrf = 0
    ): void {
        $insertChallenge = "INSERT IGNORE INTO challengeStatus "
            . "(id,user_name,reflective_xss,stored_xss, sqli,csrf,csrf_referrer"
            . ") VALUE (NULL,:user,:rxss,:sxss,:sqli,:csrf,:csrfr)";

        // set test referrer
        $csrfReferrer = ($csrf === 1) ? "referrer" : "";

        $stmt = get_login_db()->prepare($insertChallenge);
        $stmt->execute([
            'user' => $name,
            'rxss' => $reflective_xss,
            'sxss' => $stored_xss,
            'sqli' => $sqli,
            'csrf' => $csrf,
            'csrfr' => $csrfReferrer
        ]);
    }

    /**
     * Insert a test CSRF post for a given user into the database.
     * 
     * @param string $name Name of the user.
     * @param string $msg CSRF message.
     * @param string $referrer CSRF referrer.
     */
    public static function insertCSRFPost($name, $msg, $referrer): void
    {
        $insertCSRF = "INSERT IGNORE INTO csrf_posts (post_id,user_name,"
            . "message,referrer,timestamp) VALUE (NULL,:user,:msg,:referrer,"
            . ":time)";

        $stmt = get_shop_db()->prepare($insertCSRF);
        $stmt->execute([
            'user' => $name,
            'msg' => $msg,
            'referrer' => $referrer,
            'time' => date("Y-m-d H:i:s")
        ]);
    }

    /**
     * Insert challenge solutions for a given user.
     * 
     * @param string $name Name of the user.
     * @param string $rxss Challenge solution.
     * @param string $sxss Challenge solution.
     * @param string $sqli Challenge solution.
     */
    public static function insertSolutions($name, $rxss, $sxss, $sqli)
    {
        $insertSolution = "INSERT IGNORE INTO challenge_solutions (id, "
            . "user_name, reflective_xss, stored_xss, sqli) VALUE (NULL, :user,"
            . " :rxss, :sxss, :sqli)";

        $stmt = get_shop_db()->prepare($insertSolution);
        $stmt->execute([
            'user' => $name,
            'rxss' => $rxss,
            'sxss' => $sxss,
            'sqli' => $sqli
        ]);
    }

    /**
     * Insert a test product into the cart for a given user.
     * 
     * @param string $name Name of the user.
     * @param int $product Product ID.
     * @param int $quantity Product Quantity.
     */
    public static function insertProduct($name, $product, $quantity): void
    {
        $insertProd = "INSERT IGNORE INTO cart (position_id, prod_id, "
            . "user_name, quantity,timestamp) VALUE (NULL, :prod, :user, "
            . ":quantity, :timestamp)";

        $stmt = get_shop_db()->prepare($insertProd);
        $stmt->execute([
            'prod' => $product,
            'user' => $name,
            'quantity' => $quantity,
            'timestamp' => date("Y-m-d H:i:s")
        ]);
    }

    /**
     * Deletes all database entries for a given user.
     * 
     * @param string $name Name of the user.
     */
    public static function deleteDbEntries($name): void
    {
        // array of all table names
        $loginDB = ["challengeStatus", "fakeCookie", "users"];
        $shopDB = ["cart", "challenge_solutions", "csrf_posts"];

        // delete all shop DB entries
        foreach ($loginDB as $table) {
            $sql = "DELETE FROM " . $table . " WHERE user_name=?";
            get_login_db()->prepare($sql)->execute([$name]);
        }

        // delete all login DB entries
        foreach ($shopDB as $table) {
            $sql = "DELETE FROM " . $table . " WHERE user_name=?";
            get_shop_db()->prepare($sql)->execute([$name]);
        }

        // special cases:
        $sql = "DELETE FROM xss_comments WHERE author=?";
        get_shop_db()->prepare($sql)->execute([$name]);
    }
}
