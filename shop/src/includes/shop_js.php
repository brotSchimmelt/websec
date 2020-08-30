<?php
/*
* Java Script for modals
*/
// show modal with field to enter solution
if (isset($searchFieldWasUsed)) {
    if ($searchFieldWasUsed && preg_match("/document.cookie/", $rawSearchTerm)) {
        echo "
<script>
    $('#xss-solution').modal('show')
</script>";
    }
}

// show failure modal
if (isset($challengeFailed)) {
    if ($challengeFailed) {
        echo "
<script>
    $('#xss-wrong').modal('show')
</script>";
    }
}
// show success modal
if (isset($showSuccessModal)) {
    if ($showSuccessModal) {
        echo "
<script>
    $('#challenge-success').modal('show')
</script>";
    }
}

// show XSS | welcome back modal
if (isset($_SESSION['showStoredXSSModal'])) {
    if ($_SESSION['showStoredXSSModal'] == 0) {
        echo "
        <script>
            $('#xss-elliot').modal('show')
        </script>";

        $_SESSION['showStoredXSSModal'] = 1;
    }
}
