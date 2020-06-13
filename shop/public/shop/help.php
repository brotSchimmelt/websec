<?php
// includes
require("$_SERVER[DOCUMENT_ROOT]/../config/config.php");
require(HEADER_SHOP);
?>

<!doctype html>
<html lang="en">

<body>

    <h1>USER INSTRUCTIONS</h1>
    <hr>
    <h4>General Rules</h4>
    <p>
        Please read the following instructions <em>carefully</em>!
        <br>
        This website is a learning tool for the corresponding course Web Security at the University of Münster.
        This website yields security vulnerabilities that can be abused.
        These vulnerabilities are intended for learning purpose and you are not allowed to exploit these in any other way!
        <br>
        Any violation of only one of these rules will ban you from this course.
        Furthermore, in case of violation legal measures will be taken!
        <br>
        You are bound the lecturer's and tutor's instructions!
    </p>
    <p>Resetting: You can always reset every challenge. This will delete all your actions of the corresponding challenge and withdraw your achievements!</p>
    <p>External tools: All challenges can (and must) be solved without the use of external tools! We keep track of how you solve the challenges and using any software, e.g., for automation, will make you immediately fail! You are here to learn about Web hacking and not about how to run a specific toolchain.</p>
    <p>Browser Security: Most modern browsers have built-in security mechanisms to prevent attacks you need to perform here. Use an insecure browser, e.g., Microsoft Edge or Internet Explorer, for completing the challenges.</p>
    <hr>

    <h4>Cross-Site Scripting</h4>
    <p>
        This website yields security vulnerabilities that can be abused for XSS.
        You are not allowed to exploit these vulnerabilities in any other way than intended for your excercises.
        <br>
        There are two XSS challenges. The first one is a reflective XSS and simulates a search field.
        The second challenge simulates a product review page.<br>
        None of these pages yield real functionalities and are just simulations.
    </p>
    <p>
        Task: Reflective XSS<br>
        You can abuse the search field to read out a user's session ID that is stored in a cookie.<br>
        To do this you will have to create a JavaScript code snippet that displays the document's cookie.<br>
        Note the desired session ID. You will need it.
    </p>
    <p>
        Task: Stored XSS<br>
        The product reviews are stored in a database. Your task is to create a javascript code that will pop up a window displaying the session ID you obtained in the reflective XSS challenge.<br>
    </p>
    <hr>

    <h4>SQL Injections</h4>
    <p>
        For SQLi challenges you will have a personal database.
        You are not allowed to use automatic scripts on this database.
        You must not take any actions to increase the database size more than necessary!
        You have to keep the database size as small as possible.
        We may delete or reset your database any time and it will be reset automatically if it grows too big!<br>
        The SQLi challenges are a simulation of a user database.
    </p>
    <p>
        Task: Inject Account<br>
        The database yields a table named <em>users</em> containing all data of registered website users. Sadly, you do not know anything about the table's structure or data.<br>
        However, your goal is to create a user account to this website. This account should have admin permissions.<br>
        Good luck!
    </p>
    <hr>

    <h4>Contact Form Challenge</h4>
    <p>
        This website has a (fake) contact form that lets you contact the support team.<br>
        Too bad that due to recent hacker activity this form has been disabled and you cannot make any request.
    </p>
    <p>
        Task: Post a Support Request<br>
        Find a way to submit a support request. Your request message needs to be "pwned". That will show them!<br>
        If you successfully posted your attack, you will see a "Thank you!" message.
    </p>
    <hr>
    <?php
    require(FOOTER_SHOP);
    require(JS_SHOP);
    ?>
</body>

</html>