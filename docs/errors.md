# Error Codes
---

All exceptions and errors have corresponding error codes. In the following section, all these codes are listed and explained. Hints and/or known solutions can also be added in this part. 

**010**: A PDO exception occurred during the connection attempt to the **login** database.
- Database credentials are wrong *(either in ```config/config.php``` or in the ```.env``` file for the Docker containers)*
- The MySQL Docker container is not running *(```docker ps | grep "db_login"```)*
- The MySQL Docker container needs at least 1 minute after start up to accept any connection attempts.

**020**: A PDO exception occurred during the connection attempt to the **shop** database.
- Database credentials are wrong *(either in ```config/config.php``` or in the ```.env``` file for the Docker containers)*
- The MySQL Docker container is not running *(```docker ps | grep "db_shop"```)*
- The MySQL Docker container needs at least 1 minute after start up to accept any connection attempts.

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
