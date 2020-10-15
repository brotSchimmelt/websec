<?php
/*
* This file contains all alerts and modals for the shop. In order to keep the 
* source code of every site as short as possible and not to reveal any hints to 
* the students, every message can be loaded individually by its php variable 
* name if necessary.
*/


/*
* Alerts
*/
$alertProductSearch = '<br>
<div class="alert alert-warning shadow-sm" role="alert">
    <b>Warning</b>: Due to recent hacker attacks, the product search function 
    is currently disabled!
</div>';
$alertProductComment = '<br>
<div class="alert alert-warning shadow-sm" role="alert">
    <b>Warning</b>: Due to recent hacker attacks, we were forced to delete some user comments!
</div>';
$alertContactField = '<br>
<div class="alert alert-success shadow" role="alert">
    <b>Thank You!</b> We have received your request and will come back to you
    very soon.<br>Very soon! Really! One day...<br>or never.
</div><br>';
$alertContactFieldClosed = '<br>
<div class="alert alert-warning shadow" role="alert">
    Dear customer,<br>
    our contact form has been temporarily disabled.<br>We were experiencing heavy hacker attacks at our website and decided<br>to shut down our services for a few days/weeks/months.<br>
    In urgent cases please contact our support team.<br>
    Thank you for you patience!<br>
</div><br>';
$alertscorecardAllSolved = '<br>
<div class="alert alert-success shadow" role="alert">
    <b>Congratulation!</b> You solved every challenge in this shop. Good job!
</div>';


/*
* Modals
*/
$modalInputXSSCookie = '<!--Modal: Input - Reflective XSS Cookie-->
<div class="modal fade" id="xss-solution" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog cascading-modal modal-avatar modal-sm" role="document">
        <!--Content-->
        <div class="modal-content">

            <!--Header-->
            <div class="modal-header wwu-green-background text-white shadow">
                <h4 class="modal-title">Enter Cookie</h4>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <!--Body-->
            <div class="modal-body text-center mb-1">
                <div class="md-form ml-0 mr-0">
                    <form class="form-signin" action="overview.php" method="post">
                        <p>Have you found the XSS session cookie?</p>
                        <input class="form-control" type="text" name="xss-cookie" id="xss-cookie" placeholder="XSS_YOUR_SESSION" autofocus required>
                        <div class="text-center mt-4">
                            <button type="submit" class="btn btn-wwu-cart" name="xss-cookie-submit" id="xss-cookie-submit">Send</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<!--Modal END-->';
$modalErrorXSSCookieWrong = '<!--Modal: Error - Reflective XSS Cookie Wrong-->
<div class="modal fade" tabindex="-1" role="dialog" id="xss-wrong" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header bg-danger text-white shadow">
                <h4 class="modal-title">Wrong Cookie</h4>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p>Sorry, that was not correct. Please try again!</p>
                <p><strong>Hint</strong>: The cookie you are looking for is called <i>XSS_YOUR_SESSION</i>.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger text-white" data-dismiss="modal">Okay</button>
            </div>
        </div>
    </div>
</div>
<!--Modal END-->';
$modalSuccessReflectiveXSS = '<!--Modal: Success - Reflective XSS Cookie Challenge SOLVED-->
<div class="modal fade bottom" tabindex="-1" role="dialog" id="challenge-success" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header wwu-green-background text-white shadow">
                <h4 class="modal-title">Congratulation</h4>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p>You have solved this challenge!</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-wwu-cart" data-dismiss="modal">Okay</button>
            </div>
        </div>
    </div>
</div>
<!--Modal END-->';
$modalInfoStolenSession = '<!--Modal: Info - Stolen Session Elliot-->
<div class="modal fade" tabindex="-1" role="dialog" id="xss-elliot" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header wwu-green-background text-white shadow">
                <h4 class="modal-title">Welcome back, Elliot!</h4>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p>
                    We saved your last cart for you. If you want you can finalize your purchase or
                    add more products to your cart, like our top rated banana slicer. Every household should have one!
                </p>
            </div>
            <div class="modal-footer">
                <a class="btn btn-wwu-cart" href="/shop/overview.php" role="button">Go to products</a>
                <button type="button" class="btn btn-outline-secondary" data-dismiss="modal">Got it!</button>
            </div>
        </div>
    </div>
</div>
<!--Modal END-->';
$modalSuccessStoredXSS = '<!--Modal: Success - Stored XSS Cookie Challenge SOLVED-->
<div class="modal fade bottom" tabindex="-1" role="dialog" id="challenge-success-stored-xss" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header wwu-green-background text-white shadow">
                <h4 class="modal-title">Congratulation</h4>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p>You have solved the stored XSS challenge!</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-wwu-cart" data-dismiss="modal">Okay</button>
            </div>
        </div>
    </div>
</div>
<!--Modal END-->';
$modalSuccessResetAll = '<!--Modal: Success - Reset All Challenges-->
<div class="modal fade bottom" tabindex="-1" role="dialog" id="reset-all-success" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header wwu-green-background text-white shadow">
                <h4 class="modal-title">All Challenges Successfully Reset</h4>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p>All challenges have successfully been reset.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-wwu-cart" data-dismiss="modal">Okay</button>
            </div>
        </div>
    </div>
</div>
<!--Modal END-->';
$modalSuccessResetReflectiveXSS = '<!--Modal: Success - Reset Reflective XSS Challenge-->
<div class="modal fade bottom" tabindex="-1" role="dialog" id="reset-reflective-success" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header wwu-green-background text-white shadow">
                <h4 class="modal-title">Challenge Successfully Reset</h4>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p>The database for the reflective XSS challenge was successfully reset and all relevant cookies were updated.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-wwu-cart" data-dismiss="modal">Okay</button>
            </div>
        </div>
    </div>
</div>
<!--Modal END-->';
$modalSuccessResetStoredXSS = '<!--Modal: Success- Reset Stored XSS Challenge-->
<div class="modal fade bottom" tabindex="-1" role="dialog" id="reset-stored-success" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header wwu-green-background text-white shadow">
                <h4 class="modal-title">Challenge Successfully Reset</h4>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p>The database for the stored XSS challenge was successfully reset and all relevant cookies were updated.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-wwu-cart" data-dismiss="modal">Okay</button>
            </div>
        </div>
    </div>
</div>
<!--Modal END-->';
$modalSuccessResetSQLi = '<!--Modal: Success - Reset SQLi Challenge-->
<div class="modal fade bottom" tabindex="-1" role="dialog" id="reset-sqli-success" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header wwu-green-background text-white shadow">
                <h4 class="modal-title">Challenge Successfully Reset</h4>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p>The database for the SQLi challenge was successfully reset.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-wwu-cart" data-dismiss="modal">Okay</button>
            </div>
        </div>
    </div>
</div>
<!--Modal END-->';
$modalSuccessResetCSRF = '<!--Modal: Success - Reset CSRF Challenge-->
<div class="modal fade bottom" tabindex="-1" role="dialog" id="reset-csrf-success" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header wwu-green-background text-white shadow">
                <h4 class="modal-title">Challenge Successfully Reset</h4>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p>The database for the CSRF challenge was successfully reset.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-wwu-cart" data-dismiss="modal">Okay</button>
            </div>
        </div>
    </div>
</div>
<!--Modal END-->';
$modalSuccessSQLi = '<!--Modal: Success - SQLi Challenge SOLVED-->
<div class="modal fade bottom" tabindex="-1" role="dialog" id="challenge-success-sqli" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header wwu-green-background text-white shadow">
                <h4 class="modal-title">Congratulation</h4>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p>You have solved the SQLi challenge!</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-wwu-cart" data-dismiss="modal" onclick="return RefreshPage();">Okay</button>
            </div>
        </div>
    </div>
</div>
<!--Modal END-->';
$modalInfoSQLiWrongUser = '<!--Modal: Info - SQLi Wrong Premium User-->
<div class="modal fade bottom" tabindex="-1" role="dialog" id="challenge-info-sqli-wrong-premium" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header wwu-blue-background text-white shadow">
                <h4 class="modal-title">Info</h4>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p>It is nice of you that you upgraded another user to premium, but the challenge is to upgrade yourself.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-wwu-primary" data-dismiss="modal">Okay</button>
            </div>
        </div>
    </div>
</div>
<!--Modal END-->';
$modalInfoNoPremium = '<!--Modal: Info - SQLi User Added But No Premium-->
<div class="modal fade bottom" tabindex="-1" role="dialog" id="challenge-info-sqli-wrong-user" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header wwu-blue-background text-white shadow">
                <h4 class="modal-title">Info</h4>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p>You have added a new user to the database. Now try to upgrade yourself to a premium account.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-wwu-primary" data-dismiss="modal">Okay</button>
            </div>
        </div>
    </div>
</div>
<!--Modal END-->';
$modalSuccessCSRF = '<!--Modal: Success - CSRF Challenge SOLVED-->
<div class="modal fade bottom" tabindex="-1" role="dialog" id="challenge-success-csrf" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header wwu-green-background text-white shadow">
                <h4 class="modal-title">Congratulation</h4>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p>You have solved the CSRF challenge.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-wwu-cart" data-dismiss="modal">Okay</button>
            </div>
        </div>
    </div>
</div>
<!--Modal END-->';
$modalSuccessCSRFWrongReferrer = '<!--Modal: Success - CSRF Challenge SOLVED (but wrong referrer)-->
<div class="modal fade bottom" tabindex="-1" role="dialog" id="challenge-success-csrf-referrer" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header bg-warning text-white shadow">
                <h4 class="modal-title">Congratulation, I guess</h4>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p>You have probably solved the CSRF challenge. <b>But</b> it seems like you
                    manipulated the contact form or used a special tool. It is up to the lecturer
                    to decide if you passed this challenge or not.</p>
                <p>You can also try this challenge again if you use the reset function.
                    <i>Hint: </i>Try to utilize a text form on one of the other Websec Shop pages.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-warning text-white" data-dismiss="modal">Okay</button>
            </div>
        </div>
    </div>
</div>
<!--Modal END-->';
$modalSuccessCSRFWrongMessage = '<!--Modal: Success - CSRF Challenge SOLVED (but wrong message)-->
<div class="modal fade bottom" tabindex="-1" role="dialog" id="challenge-success-csrf-pwned" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header wwu-green-background text-white shadow">
                <h4 class="modal-title">Congratulation</h4>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p>You should have sent <i>pwned</i> but ok. Challenge passed!</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-wwu-cart" data-dismiss="modal">Okay</button>
            </div>
        </div>
    </div>
</div>
<!--Modal END-->';
$modalInfoCSRFAlreadyPosted = '<!--Modal: Info - CSRF Challenge already post in database-->
<div class="modal fade bottom" tabindex="-1" role="dialog" id="challenge-info-csrf-already-posted" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header wwu-blue-background text-white shadow">
                <h4 class="modal-title">Information</h4>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p>You have already posted a request. If you want to try again, you can reset the challenge in the account menu.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-wwu-primary" data-dismiss="modal">Got It!</button>
            </div>
        </div>
    </div>
</div>
<!--Modal END-->';
$changeDefaultPwdReminder = '<!--Modal: Info - Default admin user has to change the default password-->
<div class="modal fade bottom" tabindex="-1" role="dialog" id="pwd-change-reminder" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header bg-warning text-white shadow">
                <h4 class="modal-title">Reminder!</h4>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p>
                Don\'t forget to change the default password for the <i>administrator</i> user under <b>Account</b>!
                </p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-warning text-white" data-dismiss="modal">Got It!</button>
            </div>
        </div>
    </div>
</div>
<!--Modal END-->';
$modalErrorCSRFUserMismatch = '<!--Modal: Error - CSRF Challenge user mismatch-->
<div class="modal fade bottom" tabindex="-1" role="dialog" id="challenge-info-csrf-user-mismatch" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header bg-danger text-white shadow">
                <h4 class="modal-title">Information</h4>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p>
                It seems like you are trying to post a request for a different user. It should look like the user "Elliot" made the request.
                </p>
                <p>
                If you are trying to solve this challenge on hard, ensure that you use "Elliots" token. Maybe there is somewhere on this site a user database with tokens
                that is vulnerable to SQL based attacks. Who knows.
                </p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger" data-dismiss="modal">Got It!</button>
            </div>
        </div>
    </div>
</div>
<!--Modal END-->';
$modalConfirmDeleteCart = '<!--Modal: Info - Confirm to delete your cart-->
<div class="modal fade" tabindex="-1" role="dialog" id="delete-cart" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header bg-danger text-white shadow">
                <h4 class="modal-title">Confirmation</h4>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
            <p>Are you sure you want to delete all items in your cart?</p>

            </div>
            <div class="modal-footer">
            <form action="cart.php" method="post">
                <input type="hidden" name="doit-delete" value="1">
                <input class="btn btn-danger btn" type="submit" value="Delete all items">
            </form>
            </div>
        </div>
    </div>
</div>
<!--Modal END-->';
$modalGreeting = '<!-- Greeting Modal -->
<div class="modal fade" id="greeting" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="greetingLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-scrollable modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header wwu-green-background text-white shadow">
                <h3 class="modal-title" id="greetingLabel">Instructions</h3>
            </div>
            <div class="modal-body">
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
                        You are bound by the lecturer\'s and tutor\'s instructions!
                    </p>
                    <p>
                        Resetting: You can always reset every challenge. This will delete all your actions of the corresponding challenge and withdraw your achievements!
                        This can be done by accessing the challenge settings in the account menu.
                    </p>
                    <p>External tools: All challenges can (and must) be solved without the use of external tools! We keep track of how you solve the challenges and using any software, e.g., for automation, will make you immediately fail! You are here to learn about web hacking and not about how to run a specific toolchain.</p>
                    <p><b>(TODO: add versions and/or OS information)</b>Browser Support: This website was tested with Google Chrome, Firefox and Safari. If you think one or more challenges are not solvable with your browser, try an insecure one like Microsoft Edge oder Microsoft Internet Explorer.</p>
                <hr>

                <h4>Cross-Site Scripting</h4>
                    <p>
                        This website yields security vulnerabilities that can be abused for XSS.
                        You are not allowed to exploit these vulnerabilities in any other way than intended for your exercises.
                        <br>
                        There are two XSS challenges. The first one is a reflective XSS and simulates a search field.
                        The second challenge simulates a product page with a comment field.<br>
                    </p>
                    <p>
                        <b>Task: Reflective XSS</b><br>
                        You can abuse the search field to read out a user\'s session ID that is stored in a cookie.<br>
                        To do this you will have to create a JavaScript code snippet that displays the document\'s cookie.<br>
                        Note or copy the obtained session ID. The site will detect if you found the session ID and will either show you a popup where you can enter the session ID or display a button beneath the search results to trigger said popup manually.
                        This depends on the way you obtained the session ID.
                    </p>
                    <p>
                        <b>Task: Stored XSS</b><br>
                        The product reviews are stored in a database. Your task is to create a JavaScript code snippet that simulates a cookie stealing attack.<br>
                        Luckily, you are a very well prepared attacker and you have already created a PHP page <em>cookie.php</em> in the root directory of your webserver <em>evil.domain</em>.
                        You have planed to obtain the session ID cookies for every visitor of the product review page by passing them as a GET variable to your PHP page. As a reminder, a GET variable is simply appended to the end of an URL with a ? followed by its name and its value (e.g. example.com?name=value).
                        To make things easier, you only have to show a JavaScript popup to the visitors with the link to your PHP page followed by their session ID as a GET variable. As soon as someone visits the site you will receive a popup with their session ID and an option to steal their session. This will probably happen rather quickly since this is a VERY popular site.
                        If you have successfully stolen the session of your victim, you should manually manipulate his/her shopping cart by adding a Banana Slicer. Everyone should have one these days!
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
                        <b>Task: Inject Account</b><br>
                        The database yields a table named <em>users</em> containing all data of registered website users. Sadly, you do not know anything about the table\'s structure or data.<br>
                        However, your goal is to update your user status to <em>premium</em>.<br>
                        Good luck!
                    </p>
                <hr>

                <h4>Contact Form Challenge</h4>
                    <p>
                        This website has a (fake) contact form that lets you contact the support team.<br>
                        Too bad that due to recent hacker activity this form has been disabled and you cannot make any request.
                    </p>
                    <p>
                        <b>Task: Post a Support Request</b><br>
                        Find a way to submit a support request for the user <em>elliot</em>. Your request message needs to be "pwned". That will show them!<br>
                        If you successfully posted your attack, you will see a "Thank you!" message.
                    </p>
                <div class="text-center justify-content-center">
                    <br>
                    <form class="form-signin" action="main.php" method="post">
                        <div class="form-check">
                            <input type="checkbox" class="form-check-input" id="check" name="check" required>
                            <label class="form-check-label" for="check">I\'ve read the instructions!</label>
                        </div>
                        <button type="submit" name="unlock-submit" id="unlock-btn" class="btn btn-wwu-cart mt-2">Let\'s Go!</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<!--Modal END-->';

// old footer #greeting
// </div>
// <div class="modal-footer text-center justify-content-center">
