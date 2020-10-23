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
    our contact form has been temporarily <b>disabled</b>. We were experiencing heavy hacker attacks at our website and decided to shut down our services for a few days/weeks/months.<br>
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
                <button type="button" class="btn btn-outline-secondary" onclick="RefreshPage();" data-dismiss="modal">Got it!</button>
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
