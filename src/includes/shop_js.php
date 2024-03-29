<script src="../assets/js/shop.js"></script>
<?php
/*
* Java Script for modals
*/

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

// reset CSRF success modal
if (isset($resetCSRFModal)) {
    if ($resetCSRFModal) {
        echo $modalSuccessResetCSRF;
        echo "<script>$('#reset-csrf-success').modal('show')</script>";
    }
}

// reset all challenges success modal
if (isset($resetAllModal)) {
    if ($resetAllModal) {
        echo $modalSuccessResetAll;
        echo "<script>$('#reset-all-success').modal('show')</script>";
    }
}

// reset all challenges success modal
if (isset($removeCommentModal)) {
    if ($removeCommentModal) {
        echo $modalSuccessRemoveComment;
        echo "<script>$('#remove-comment').modal('show')</script>";
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
?>