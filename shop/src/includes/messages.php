<!--Modal: Reflective XSS Enter Cookie-->
<div class="modal fade" id="xss-solution" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog cascading-modal modal-avatar modal-sm" role="document">
        <!--Content-->
        <div class="modal-content">

            <!--Header-->
            <div class="modal-header">
                <h5 class="mt-2">Enter Cookie</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <!--Body-->
            <div class="modal-body text-center mb-1">
                <div class="md-form ml-0 mr-0">
                    <form class="form-signin" action="<?= $thisPage ?>" method="post">
                        <p>Have you found the XSS session cookie?</p>
                        <input type="text" name="xss-cookie" id="xss-cookie" placeholder="XSS_YOUR_SESSION" autofocus>
                        <div class="text-center mt-4">
                            <button type="submit" class="btn btn-primary" name="xss-cookie-submit" id="xss-cookie-submit">Send</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<!--END-->
<!--Modal: Reflective XSS Cookie Wrong-->
<div class="modal fade" tabindex="-1" role="dialog" id="xss-wrong" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Wrong Cookie</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p>Sorry, that was not correct. Please try again!</p>
                <p><strong>Hint</strong>: The cookie you are looking for is called <i>XSS_YOUR_SESSION</i>.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-warning" data-dismiss="modal">Okay</button>
            </div>
        </div>
    </div>
</div>
<!--END-->
<!--Modal: Reflective XSS Cookie Challenge SOLVED-->
<div class="modal fade bottom" tabindex="-1" role="dialog" id="challenge-success" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Congratulation</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p>You have solved this challenge!</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-success" data-dismiss="modal">Okay</button>
            </div>
        </div>
    </div>
</div>
<!--END-->
<!--Modal: Stored XSS | Welcome Elliot-->
<div class="modal fade" tabindex="-1" role="dialog" id="xss-elliot" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Welcome back, Elliot!</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
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
                <a class="btn btn-outline-secondary" href="/shop/overview.php" role="button">Go to products</a>
                <button type="button" class="btn btn-success" data-dismiss="modal">Got it!</button>
            </div>
        </div>
    </div>
</div>
<!--END-->
<!--Modal: Stored XSS Cookie Challenge SOLVED-->
<div class="modal fade bottom" tabindex="-1" role="dialog" id="challenge-success-stored-xss" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Congratulation</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p>You have solved the stored XSS challenge!</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-success" data-dismiss="modal">Okay</button>
            </div>
        </div>
    </div>
</div>
<!--END-->
<!--Modal: Reflective XSS Challenge Reset Success-->
<div class="modal fade bottom" tabindex="-1" role="dialog" id="reset-reflective-success" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Challenge successfully reset</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p>The database for the reflective XSS challenge was successfully reset and all relevant cookies were updated.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-success" data-dismiss="modal">Okay</button>
            </div>
        </div>
    </div>
</div>
<!--END-->
<!--Modal: Reflective XSS Challenge Reset Success-->
<div class="modal fade bottom" tabindex="-1" role="dialog" id="reset-stored-success" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Challenge successfully reset</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p>The database for the stored XSS challenge was successfully reset and all relevant cookies were updated.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-success" data-dismiss="modal">Okay</button>
            </div>
        </div>
    </div>
</div>
<!--END-->
<!--Modal: SQLi Challenge SOLVED-->
<div class="modal fade bottom" tabindex="-1" role="dialog" id="challenge-success-sqli" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Congratulation</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p>You have solved the SQLi challenge!</p>
            </div>
            <div class="modal-footer">
                <!-- <button type="button" class="btn btn-success" data-dismiss="modal">Okay</button> -->
                <button type="button" class="btn btn-success" data-dismiss="modal" onclick="return RefreshPage();">Okay</button>
            </div>
        </div>
    </div>
</div>
<!--END-->
<!--Modal: SQLi Challenge Info Wrong Premium User-->
<div class="modal fade bottom" tabindex="-1" role="dialog" id="challenge-info-sqli-wrong-premium" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Info</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p>It is nice of you that you upgraded another user to premium, but the challenge is to upgrade yourself.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-success" data-dismiss="modal">Okay</button>
            </div>
        </div>
    </div>
</div>
<!--END-->
<!--Modal: SQLi Challenge Info User Added But No Premium-->
<div class="modal fade bottom" tabindex="-1" role="dialog" id="challenge-info-sqli-wrong-user" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Info</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p>You have added a new user to the database. Now try to upgrade yourself to premium.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-success" data-dismiss="modal">Okay</button>
            </div>
        </div>
    </div>
</div>
<!--END-->
<!--Alert: Product Search is closed -->
<?php
$alertProductSearch = '<br>
<div class="alert alert-warning shadow-sm" role="alert">
    <b>Warning</b>: Due to recent hacker attacks, the product search function 
    is currently disabled!
</div>';
?>
<!--END-->
<!--Alert: Product Search is closed -->
<?php
$alertCommentField = '<br>
<div class="alert alert-warning shadow-sm" role="alert">
    <b>Warning</b>: Due to recent hacker attacks, the comment function 
    is currently disabled!
</div>';
?>
<!--END-->