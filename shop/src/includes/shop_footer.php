<footer id="main-footer">
    <div id="footer-container">

        <div id="footer-left-column">
            <a href="#">Image Source</a>
        </div>

        <div id="footer-middle-column">
            <button class="btn btn-outline-warning" data-toggle="modal" data-target="#help-modal">Help for the Exercises</button>
        </div>

        <div id="footer-right-column">
            <a href="#">Back to the top</a>
        </div>

    </div>
</footer>

<!-- Help Modal -->
<div class="modal fade text-light" id="help-modal">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="new-id">
                    Help
                </h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <h5>Cross-Site Scripting</h5>
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

                <h5>SQL Injections</h5>
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

                <h5>Contact Form Challenge</h5>
                <p>
                    This website has a (fake) contact form that lets you contact the support team.<br>
                    Too bad that due to recent hacker activity this form has been disabled and you cannot make any request.
                </p>
                <p>
                    Task: Post a Support Request<br>
                    Find a way to submit a support request. Your request message needs to be "pwned". That will show them!<br>
                    If you successfully posted your attack, you will see a "Thank you!" message.
                </p>
            </div>
            <div class="modal-footer" id="modal-close-btn">
                <button type="button" class="btn btn-primary" data-dismiss="modal" id="ok-btn">OK</button>
            </div>
        </div>
    </div>
</div>