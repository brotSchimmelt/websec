<hr class="footer-line">
<footer class="container py-5">
    <div class="row">
        <div class="col-12 col-md">
            <a class="py-2d-none d-md-inline-block pb-3" href="/shop/main.php" aria-label="Main Page">
                <img class="mt-1 mb-2" src="/assets/img/wwu_cysec.png" width="100" height="45">
            </a>
            <small class="d-block mb-3 text-muted">&copy; <?php get_semester() ?></small>
        </div>
        <div class="col-6 col-md">
            <h5 class="green">Instructions</h5>
            <ul class="list-unstyled text-small">
                <li><a class="text-muted" href="#">General</a></li>
                <li><a class="text-muted" href="#">XSS</a></li>
                <li><a class="text-muted" href="#">SQLi</a></li>
                <li><a class="text-muted" href="#">CSRF</a></li>
            </ul>
        </div>
        <div class="col-6 col-md">
            <h5 class="green">Help</h5>
            <ul class="list-unstyled text-small">
                <li><a class="text-muted" href="#">some</a></li>
                <li><a class="text-muted" href="#">list</a></li>
                <li><a class="text-muted" href="#">elements</a></li>
            </ul>
        </div>
        <div class="col-6 col-md">
            <h5 class="green">Contact Instructor</h5>
            <ul class="list-unstyled text-small">
                <li><a class="text-muted" href="#">mail</a></li>
                <li><a class="text-muted" href="#">phone</a></li>
                <li><a class="text-muted" href="#">address</a></li>
                <li><a class="text-muted" href="#">time</a></li>
            </ul>
        </div>
        <div class="col-6 col-md">
            <a class="py-2d-none d-md-inline-block pb-3" href="#" aria-label="Main Page">
                <svg width="2.5em" height="2.5em" viewBox="0 0 16 16" class="bi bi-arrow-bar-up" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                    <path fill-rule="evenodd" d="M11.354 5.854a.5.5 0 0 0 0-.708l-3-3a.5.5 0 0 0-.708 0l-3 3a.5.5 0 1 0 .708.708L8 3.207l2.646 2.647a.5.5 0 0 0 .708 0z" />
                    <path fill-rule="evenodd" d="M8 10a.5.5 0 0 0 .5-.5V3a.5.5 0 0 0-1 0v6.5a.5.5 0 0 0 .5.5zm-4.8 1.6c0-.22.18-.4.4-.4h8.8a.4.4 0 0 1 0 .8H3.6a.4.4 0 0 1-.4-.4z" />
                </svg>
            </a>
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