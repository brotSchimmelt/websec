<?php if (get_global_difficulty() != "hard") : ?>
    <!-- Normal -->
    <h4 class="text-wwu-green">Contact Form Challenge</h4>
    <p>
        This website has a (fake) contact form that lets you contact the support team.<br>
        Too bad that due to recent hacker activity this form has been disabled and you cannot make any request.
    </p>
    <p>
        <b>Task: Post a Support Request</b><br>
        Find a way to submit a support request for the user <em>elliot</em>. Your request message needs to be "pwned". That will show them!<br>
        If you successfully posted your attack, you will see a "Thank you!" message.
    </p>
    <br>
<?php else : ?>
    <!-- Hard -->
    TODO: Write instructions.
<?php endif; ?>