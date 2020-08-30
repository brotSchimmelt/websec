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
                        <input type="text" name="xss-cookie" id="xss-cookie" placeholder="XSS_Your_Session" autofocus>
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
                <p><strong>Hint</strong>: The cookie you are looking for is called <i>XSS_Your_Session</i>.</p>
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
<!--Alert: Product Search is closed -->
<?php
$alertProductSearch = '<br>
<div class="alert alert-warning shadow-sm" role="alert">
    <b>Warning</b>: Due to recent hacker attacks, the product search function 
    is currently disabled!
</div>';
?>

<!--END-->