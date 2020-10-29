# SQLite Databases

This directory contains all user SQLite databases for the SQL injection challenge.

## Setup

The databases are created during the registration process with the ```create_sqli_db($username, $mail)``` function in **src/websec_functions.php**.

On **normal** difficulty the databases are initialized with one table *('users')* that stores username, password, email, whish list and user_status for every entry. The database is filled with a set of fake users and an entry for the student himself. The password that is displayed for the student is a random string, not related to the actual password hash stored in the 'real' MySQL login database.

```php
<?php
// Initial setup of the SQLite database on 'normal' difficulty
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
?>
```

On **hard** difficulty the above mentioned table is extended by a second one *('premium_users')* in which the premium user status is stored for every fake user *(and the student respectively)*.

```php
// Initial setup of the SQLite database on 'hard' difficulty
<?php
$database->exec('CREATE TABLE users (username text NOT NULL, '
                    . 'password text, email text, wishlist text, token '
                    . 'text NOT NULL);');

$database->exec('CREATE TABLE premium_users (username text NOT '
                    . 'NULL, status text NOT NULL);');

$database->exec("INSERT INTO users (username,password,email,"
                    . "wishlist, token) VALUES ('admin','admin',"
                    . "'admin@admin.admin', 'new Mug', '"
                    . str_shuffle($genericToken) . "');");

$database->exec("INSERT INTO premium_users (username,status) "
                    . "VALUES ('admin','standard');");

$database->exec("INSERT INTO users (username,password,email,"
                    . "wishlist, token) VALUES ('elliot','toor',"
                    . "'alderson@allsafe.con', 'Banana Slicer', '"
                    . $challengeToken . "');");

$database->exec("INSERT INTO premium_users (username,status) "
                    . "VALUES ('elliot','standard');");

$database->exec("INSERT INTO users (username,password,email,"
                    . "wishlist, token) VALUES ('l337_h4ck3r','password123',"
                    . "'girly95@hotmail.con', 'T-Shirt', '"
                    . str_shuffle($genericToken) . "');");

$database->exec("INSERT INTO premium_users (username,status) "
                    . "VALUES ('l337_h4ck3r','premium');");

$database->exec("INSERT INTO users (username,password,email,"
                    . "wishlist, token) VALUES ('" . $username . "','"
                    . $fakePwdHash . "', '" . $mail . "', 'empty', '"
                    . str_shuffle($genericToken) . "');");

$database->exec("INSERT INTO premium_users (username,status) "
                    . "VALUES ('" . $username . "','standard');");
?>
```

Furthermore, the databases contain a fake token on **hard** difficulty that is needed for the CSRF challenge.

## Reset
The student can choose to reset the database in the challenge settings. In this case the database is removed from the **data/** directory and re-created as displayed above.


## Change Challenge Difficulty

In order to change the difficulty for the SQLi challenge, the SQLite database needs to be deleted and initialized with the corresponding structure and entries for the new difficulty (see the code above).
