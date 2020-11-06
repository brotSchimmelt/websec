<?php if (get_global_difficulty() != "hard") : ?>
    <!-- Normal -->
    <h4 class="text-wwu-green">SQL Injections</h4>
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
        The database yields a table named <em>users</em> containing all the data of registered website users. Sadly, you do not know anything about the table's structure or data.<br>
        However, your goal is to update your user status to <em>premium</em>.<br>
        Good luck!
    </p>
    <br>
<?php else : ?>
    <!-- Hard -->
    <h4 class="text-wwu-green">SQL Injections</h4>
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
        The SQLite database yields multiple tables. One of them is the <em>users</em> table. It contains all registered users of this website.
        Sadly, you do not know anything else about the database or the other tables.<br>
        However, your goal is to update your user status to <em>premium</em>.<br>
        Good luck!
    </p>
    <br>
<?php endif; ?>