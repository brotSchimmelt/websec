# websec

## Project Structure
```
shop/
├── bin/
│   └── Command Line Tools
├── config/
│   └── config.php
│   └── DB_connections.php
├── docs/
│   └── Documentation of the Project
├── public/
│   └── index.php
│   └── Webserver Files in general
├── src/
│   └── PHP Source Code
├── tests/
│   └── PHP Unit Tests
└── vendor
    └── Used Components (mainly PHPUnit)
```

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


## Errors & Exceptions

// TODO: Exceptions vs. error Codes --> Erläuterung

### Message Types

// TODO: Clean up ...

success= ...
  - login [main.php] ??newType
  - prodAdded [SHOP] ??newType
  - logout [login] --> check
  - resetPwd (password zurückgesetzt) [login] --> check
  - requestProcessed (email versandt, falls adresse im system) [pwd forgotten] --> check
  - pwdChanged (Passwort normal geändert) [pwd change site]

error= ...
  - missingToken (pwd reset) --> check
  - sqlError (versch. Variatnen) !Code --> check
  - wrongCredentials (login failed) --> check
  - internalError (falls password_verify versagt) !Code --> check
  - invalidNameAndMail [register] --> check
  - invalidUsername [register] --> check
  - invalidMail [register] --> check
  - invalidPassword [register] [change Pwd] --> check
  - passwordMismatch [register] [change Pwd] --> check
  - nameError [register] --> check
  - mailTaken [register] --> check
  - invalidToken [login] (Kontext: Password reset) --> check
  - invalidMailFormat [register] --> check


### Error Code Meaning [Optional Error Output]

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

**1xx**: A SQL query could not be processed by the login or shop database.
*[Login Database]*
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
- **115**: The fake cookie could not be fetched from the users table. See query in ```function check_xss_challenge```
- **116**: The XSS Comment could not be fetched from the databse. See query in ```function check_xss_challenge```
- **117**: The CSRF posts could not be fetched from the databse. See query in ```function check_crosspost_challenge```
- **118**: The CSRF referrer could not be fetched from the databse. See query in ```function check_crosspost_challenge_double```
- **119**: The XSS challenge cookie could not be inserted into the database during registration. See query in ```function do_registration```

*[Shop Database]*
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








// example with hash
```php
<?php
//create function with an exception
function checkNum($number) {
  if($number>1) {
    throw new Exception("Value must be 1 or below");
  }
  return true;
}

//trigger exception in a "try" block
try {
  checkNum(2);
  //If the exception is thrown, this text will not be shown
  echo 'If you see this, the number is 1 or below';
}

//catch exception
catch(Exception $e) {
  echo 'Message: ' .$e->getMessage();
}
?>
```
