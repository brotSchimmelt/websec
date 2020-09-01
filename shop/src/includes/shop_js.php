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
// input modal for reflective XSS challenge
if (isset($searchFieldWasUsed)) {
    if ($searchFieldWasUsed && preg_match("/document.cookie/", $rawSearchTerm)) {
        echo $modalInputXSSCookie;
        echo "<script>$('#xss-solution').modal('show')</script>";
    }
}

// error modal for reflective XSS challenge
if (isset($challengeFailed)) {
    if ($challengeFailed) {
        echo $modalErrorXSSCookieWrong;
        echo "<script>$('#xss-wrong').modal('show')</script>";
    }
}
// success modal for reflective XSS challenge
if (isset($showSuccessModal)) {
    if ($showSuccessModal) {
        echo $modalSuccessReflectiveXSS;
        echo "<script>$('#challenge-success').modal('show')</script>";
    }
}

// stored XSS | welcome back modal
if (isset($_SESSION['showStoredXSSModal'])) {
    if ($_SESSION['showStoredXSSModal'] == 0) {
        echo $modalInfoStolenSession;
        echo "<script>$('#xss-elliot').modal('show')</script>";

        $_SESSION['showStoredXSSModal'] = 1;
    }
}

// stored XSS success modal
if (isset($_SESSION['showSuccessModalXSS'])) {
    if ($_SESSION['showSuccessModalXSS'] == 0) {
        echo $modalSuccessStoredXSS;
        echo "<script>$('#challenge-success-stored-xss').modal('show')</script>";

        $_SESSION['showSuccessModalXSS'] = 1;
    }
}

// reset reflective XSS success modal
if (isset($resetReflectiveXSSModal)) {
    if ($resetReflectiveXSSModal) {
        echo $modalSuccessResetReflectiveXSS;
        echo "<script>$('#reset-reflective-success').modal('show')</script>";
    }
}

// reset stored XSS success modal
if (isset($resetStoredXSSModal)) {
    if ($resetStoredXSSModal) {
        echo $modalSuccessResetStoredXSS;
        echo "<script>$('#reset-stored-success').modal('show')</script>";
    }
}

// reset SQLi success modal
if (isset($resetSQLiModal)) {
    if ($resetSQLiModal) {
        echo $modalSuccessResetSQLi;
        echo "<script>$('#reset-sqli-success').modal('show')</script>";
    }
}

// challenge modals for SQLi challenge
if (isset($queryResultModal)) {
    if ($queryResultModal == 0) {
        // success
        echo $modalSuccessSQLi;
        echo "<script>$('#challenge-success-sqli').modal('show')</script>";
    } elseif ($queryResultModal == 1) {
        // wrong user set to premium
        echo $modalInfoSQLiWrongUser;
        echo "<script>$('#challenge-info-sqli-wrong-premium').modal('show')"
            . "</script>";
    } elseif ($queryResultModal == 2) {
        // user added but no premium user
        echo $modalInfoNoPremium;
        echo "<script>$('#challenge-info-sqli-wrong-user').modal('show')"
            . "</script>";
    }
}

// challenge modals for CSRF challenge
if (isset($csrfResult)) {
    if ($csrfResult == 0) {
        // success
        echo $modalSuccessCSRF;
        echo "<script>$('#challenge-success-csrf').modal('show')</script>";
    } elseif ($csrfResult == 1) {
        // wrong message; still passed
        echo $modalSuccessCSRFWrongMessage;
        echo "<script>$('#challenge-success-csrf-pwned').modal('show')</script>";
    } elseif ($csrfResult == 2) {
        // error: wrong user
        echo $modalErrorCSRFUserMismatch;
        echo "<script>$('#challenge-info-csrf-user-mismatch').modal('show')"
            . "</script>";
    } elseif ($csrfResult == 3) {
        // error: already post in the database
        echo $modalInfoCSRFAlreadyPosted;
        echo "<script>$('#challenge-info-csrf-already-posted').modal('show')"
            . "</script>";
    } elseif ($csrfResult == 4) {
        // wrong referrer; still passed
        echo $modalSuccessCSRFWrongReferrer;
        echo "<script>$('#challenge-success-csrf-referrer').modal('show')"
            . "</script>";
    }
}
?>