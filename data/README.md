# Data

This directory contains the SQLite databases for the SQL injection challenge and all user input files.

## Setup SQLite Databases

The databases are created during the registration process with the ```create_sqli_db($username, $mail)``` function in **src/websec_functions.php**.

On **normal** difficulty the databases are initialized with one table *('users')* that stores user name, password, email, whish list and user status for every entry. The database is filled with a set of fake users and an entry for the student. The password that is displayed for the student is a random string, not related to the actual password hash stored in the 'real' MySQL login database.

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

On **hard** difficulty the above mentioned table is extended by a second *('premium_users')* table in which the premium user status is stored for every fake user *(and the student respectively)*.

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

Furthermore, the databases contain a fake token on **hard** difficulty that is needed to solve the CSRF challenge.

## Reset SQLite Database
The student can choose to reset the database in the challenge settings. In this case, the database is removed from the **data/** directory and recreated with the ```create_sqli_db``` function.


## Change Challenge Difficulty (SQLite)

In order to change the difficulty for the SQLi challenge, the SQLite database needs to be deleted and initialized with the corresponding structure and entries for the new difficulty (see the code samples above).

## User Input Files

All user input related to the hacking challenges is stored in a user specific JSON file, that is created when the user attempts a challenge for the first time. The user input in the JSON file is grouped by challenges.
In addition to the raw user input, the referrer for the CSRF challenge is also saved.

The purpose of these files is, to give the lecturer the option to verify that a particular challenge has been solved in the intended way and no scripts are other tools were used.

Furthermore, the user input that solved a specific challenge is also directly displayed in the results section in the admin area.

