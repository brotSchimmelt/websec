<script>
    // refresh the page
    function RefreshPage() {
        window.location.reload(true);
    }
</script>
<?php
/*
* Java Script for modals
*/
// show modal with field to enter reflective XSS solution
if (isset($searchFieldWasUsed)) {
    if ($searchFieldWasUsed && preg_match("/document.cookie/", $rawSearchTerm)) {
        echo "<script>$('#xss-solution').modal('show')</script>";
    }
}

// show reflective XSS failure modal
if (isset($challengeFailed)) {
    if ($challengeFailed) {
        echo "<script>$('#xss-wrong').modal('show')</script>";
    }
}
// show reflective XSS success modal
if (isset($showSuccessModal)) {
    if ($showSuccessModal) {
        echo "<script>$('#challenge-success').modal('show')</script>";
    }
}

// show XSS | welcome back modal
if (isset($_SESSION['showStoredXSSModal'])) {
    if ($_SESSION['showStoredXSSModal'] == 0) {
        echo "<script>$('#xss-elliot').modal('show')</script>";

        $_SESSION['showStoredXSSModal'] = 1;
    }
}

// show stored XSS success modal
if (isset($_SESSION['showSuccessModalXSS'])) {
    if ($_SESSION['showSuccessModalXSS'] == 0) {
        echo "<script>$('#challenge-success-stored-xss').modal('show')</script>";

        $_SESSION['showSuccessModalXSS'] = 1;
    }
}

// show reset reflective XSS success modal
if (isset($resetReflectiveXSSModal)) {
    if ($resetReflectiveXSSModal) {
        echo "<script>$('#reset-reflective-success').modal('show')</script>";
        echo "awesome success";
    }
}

// show reset stored XSS success modal
if (isset($resetStoredXSSModal)) {
    if ($resetStoredXSSModal) {
        echo "<script>$('#reset-reflective-success').modal('show')</script>";
    }
}

// show reset SQLi success modal
if (isset($resetSQLiModal)) {
    if ($resetSQLiModal) {
        echo "<script>$('#reset-sqli-success').modal('show')</script>";
    }
}

// show challenge modals for SQLi challenge
if (isset($queryResultModal)) {
    if ($queryResultModal == 0) {
        // success
        echo "<script>$('#challenge-success-sqli').modal('show')</script>";
    } elseif ($queryResultModal == 1) {
        // wrong user set to premium
        echo "<script>$('#challenge-info-sqli-wrong-premium').modal('show')"
            . "</script>";
    } elseif ($queryResultModal == 2) {
        // user added but no premium user
        echo "<script>$('#challenge-info-sqli-wrong-user').modal('show')"
            . "</script>";
    }
}

// show challenge modals for CSRF challenge
if (isset($csrfResult)) {
    if ($csrfResult == 0) {
        // success
        echo "<script>$('#challenge-success-csrf').modal('show')</script>";
    } elseif ($csrfResult == 1) {
        // wrong message; still passed
        echo "<script>$('#challenge-success-csrf-pwned').modal('show')</script>";
    } elseif ($csrfResult == 2) {
        // error: wrong user
        echo "<script>$('#challenge-info-csrf-user-mismatch').modal('show')"
            . "</script>";
    } elseif ($csrfResult == 3) {
        // error: already post in the database
        echo "<script>$('#challenge-info-csrf-already-posted').modal('show')"
            . "</script>";
    } elseif ($csrfResult == 4) {
        // wrong referrer; still passed
        echo "<script>$('#challenge-success-csrf-referrer').modal('show')"
            . "</script>";
    }
}
?>