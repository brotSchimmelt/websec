<hr class="footer-line">
<footer class="container py-5">
    <div class="row">
        <div class="col-12 col-md">
            <a class="py-2d-none d-md-inline-block pb-3" href="/shop/main.php" aria-label="Main Page">
                <img class="mt-1 mb-2" src="/assets/img/fake_logo.png" width="128" height="72">
            </a>
            <small class="d-block mb-3 text-muted">&copy; <?php get_semester() ?></small>
        </div>
        <div class="col-6 col-md">
            <h5 class="green">Instructions</h5>
            <ul class="list-unstyled text-small">
                <li><a class="text-muted" href="/user/help.php?help=general">General</a></li>
                <li><a class="text-muted" href="/user/help.php?help=xss">Cross-Site Scripting</a></li>
                <li><a class="text-muted" href="/user/help.php?help=sqli">SQL Injection</a></li>
                <li><a class="text-muted" href="/user/help.php?help=csrf">Contact Form</a></li>
            </ul>
        </div>
        <div class="col-6 col-md">
            <h5 class="green">Account</h5>
            <ul class="list-unstyled text-small">
                <li><a class="text-muted" href="/user/scorecard.php">Scorecard</a></li>
                <li><a class="text-muted" href="/user/challenge_settings.php">Settings</a></li>
                <li><a class="text-muted" href="/user/change_password.php">Change Password</a></li>
                <li>
                    <p class="text-muted" href="#">Difficulty: <strong><?= get_global_difficulty() ?></strong></p>
                </li>
            </ul>
        </div>
        <div class="col-6 col-md">
            <h5 class="green">Sources</h5>
            <ul class="list-unstyled text-small">
                <li><a class="text-muted" href="https://unsplash.com/license">Unsplash</a></li>
                <li><a class="text-muted" href="https://www.pexels.com/license/">Pexels</a></li>
                <li><a class="text-muted" href="https://smartmockups.com/">Smart Mockups</a></li>
                <li><a class="text-muted" href="<?= get_setting("learnweb", "link") ?>">Learnweb</a></li>
            </ul>
        </div>
        <div class="col-6 col-md">
            <a class="py-2d-none d-md-inline-block pb-3 ml-5 text-muted" href="" aria-label="Main Page">
                <svg width="2.5em" height="2.5em" viewBox="0 0 16 16" class="bi bi-arrow-bar-up" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                    <path fill-rule="evenodd" d="M11.354 5.854a.5.5 0 0 0 0-.708l-3-3a.5.5 0 0 0-.708 0l-3 3a.5.5 0 1 0 .708.708L8 3.207l2.646 2.647a.5.5 0 0 0 .708 0z" />
                    <path fill-rule="evenodd" d="M8 10a.5.5 0 0 0 .5-.5V3a.5.5 0 0 0-1 0v6.5a.5.5 0 0 0 .5.5zm-4.8 1.6c0-.22.18-.4.4-.4h8.8a.4.4 0 0 1 0 .8H3.6a.4.4 0 0 1-.4-.4z" />
                </svg>
            </a>
        </div>
    </div>
</footer>