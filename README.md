# WebSec - Hacking Plattform

This is a short summary of the most important points of the documentation for this project. 

## TODOs (remove in production)

- update instructions for the challenges
- replace dummy texts and product descriptions
- add a popup for the SQLi challenge *(on 'hard' difficulty)* to indicate that a SQLite DB is used
- remove TODOs from this README

## Installation and Setup

1. Setup the docker environment with the ```setup_docker.sh``` script *(detailed explanation in the docker README)* **XOR** follow the steps described in the docker README manually.
2. Login with the default admin user:
- **user** ```administrator```
- **password** ```dpbCpfcAqVHY3gYf```
3. Change the default password!
4. Choose the settings either in the admin area of the WebSec shop or edit them directly in the ```config/settings.json``` file. *(The difficulty of the challenges must be set before the first student login. Otherwise every student has to reset their challenges in the menu manually in order to load the new challenge settings.)*

## Project Structure
```
www/
├── bin/
│   └── command line tools
├── config/
│   └── php configuration files
│   └── shop settings JSON file
├── data/
│   └── sqlite databases for every user
├── docs/
│   └── short documentation for the project
├── public/
│   └── admin/
│   │   └── pages for the admin area
│   └── assets/
│   │   └── CSS, JavaScript and images
│   └── shop/
│   │   └── pages for the challenges
│   └── user/
│       └── pages for the user area
├── src/
│   └── includes/
│   │   └── include files
│   └── php functions
├── tests/
│   └── php unit tests
└── vendor
    └── dependencies (for PHPUnit)
```

#### bin/

Contains the command line tools for this project.
- ```convert_md_to_html.sh``` Converts markdown files *(like this one)* to valid html for the **docs/** folder.
- ```get_docker_logs.sh``` Copys log files from the separat docker containers into the **bin/** folder.


#### config/

Contains the configurations and settings for the shop.
- ```config.php``` Stores important constants for the project *(server name, paths etc.)*. Is effected by the ```setup_docker.sh``` script.
- ```db_login.php``` Contains the login credentials for the **login** database. Is also setup by the ```setup_docker.sh``` script.
- ```db_shop.php``` Contains the login credentials for the **shop** database. Is also setup by the ```setup_docker.sh``` script.
- ```settings.json``` Stores all settings for the shop. For a more detailed description see paragraph **Settings**.


#### data/

Contains all user SQLite databases for the SQLi challenge.

On **normal** difficulty the databases are initialized with one table *('users')* that stores username, password, email, whish list and user_status for every entry. The database is filled with a set of fake users and an entry for the corresponding student. The password that is displayed for the student is a random string.

On **hard** difficulty the above mentioned table is extended by a second one *('premium_users')* in which the premium user status is stored for every fake user *(and the student)*.

The databases are created during the registration process with the ```create_sqli_db($username, $mail)``` function in **src/websec_functions.php**.

#### docs/
#### public/
#### src/
#### tests/
#### vendor/


## Challenges

TODO: describe challenges briefly and how to solve them

## Settings

TODO: describe every setting and its effect


## Error Codes

**010**: A PDO exception occurred during the connection attempt to the **login** database.
- Database credentials are wrong *(either in ```config/config.php``` or in the ```.env``` file for the docker containers)*
- The MySQL docker container is not running *(```docker ps | grep "db_login"```)*

**020**: A PDO exception occurred during the connection attempt to the **shop** database.
- Database credentials are wrong *(either in ```config/config.php``` or in the ```.env``` file for the docker containers)*
- The MySQL docker container is not running *(```docker ps | grep "db_shop"```)*

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

**061**: The $.post request in ```stored_xss.js``` failed. Either the form handler was moved or the user found a way to break the JavaScript.

**1xx**: A SQL query could not be processed by the login or shop database.

#### Login Database
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

#### Shop Database
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


## Naming Conventions

**Variables**: ```variableName```

**Constants**: ```CONSTANT_NAME```

**Methods**: ```method_name```

**CSS Classes**: ```class-name```

**CSS IDs**: ```idName```

**HTML Fields etc.**: ```field-name```

**DB Fields**: ```[table]_field_name```

**PHP Files**: ```file_name.php```

**Other Asset Files**: ```file_name.ending```
