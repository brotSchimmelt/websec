<!-- Greeting Modal -->
<div class="modal fade" id="greeting" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="greetingLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-scrollable modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="greetingLabel">Instructions</h5>
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
            </div>
            <div class="modal-footer text-center">
                <form class="form-signin" action="<?= $thisPage ?>" method="post">
                    <div class="form-check">
                        <input type="checkbox" class="form-check-input" id="check" name="check" required>
                        <label class="form-check-label" for="check">I've read the instructions</label>
                    </div>
                    <button type="submit" name="unlock-submit" id="unlock-btn" class="btn btn-success mt-2">Let's Go!</button>
                </form>
            </div>
        </div>
    </div>
</div>