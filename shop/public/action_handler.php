<?php

if (isset($_POST['register-submit'])) {
    require("$_SERVER[DOCUMENT_ROOT]/../src/registration_script.php");
} else {
    // TODO: add redirect to login page!
    echo "<b>Nope.</b>";
}
