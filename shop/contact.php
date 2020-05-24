<!doctype html>
<html lang="en">

<?php include("src/header.php"); ?>


<body>

    <h2>Contact Our Support Team</h2>
    We are here for you every day, twentyfour hours a day, 365 days a year!
    <h4>Contact Form</h4>
    Dear customer,<br>
    our contact form has been temporarily disabled.<br>We were experiencing heavy hacker attacks at our website and decided<br>to shut down our services for a few days/weeks/months.<br>
    In urgent cases please contact our support team.<br>
    Thanks!<br>
    <br>
    <form action="thisfile" method="post" id="reviewform">
        your name:
        <input type="text" name="username" value="TestUser" disabled><br>
        <input type="hidden" name="uname" value="uname">
        your message for us:
        <input type="text" name="upost" size="30" disabled><br><br>
        <input type="submit" value="Submit" disabled>
    </form>

</body>
<?php include("src/footer.php"); ?>

</html>