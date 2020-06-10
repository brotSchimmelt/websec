<?php require("$_SERVER[DOCUMENT_ROOT]/../src/dashboard_header.php");
?>

<!doctype html>
<html lang="en">

<style>
    .linkBtn {
        background: none !important;
        border: none;
        padding: 0 !important;
        /*optional*/
        font-family: arial, sans-serif;
        /*input has OS specific font-family*/
        color: #069;
        text-decoration: underline;
        cursor: pointer;
    }
</style>

<body>

    <div class="container-fluid">
        <div class="row">
            <?php include("$_SERVER[DOCUMENT_ROOT]/../src/sidebar.php"); ?>

            <main role="main" class="col-md-9 ml-sm-auto col-lg-10 px-md-4">

            </main>
        </div>
    </div>


    <script src="resources/js/jquery-3.5.1.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>

    <script src="resources/js/bootstrap.bundle.min.js" integrity="sha384-1CmrxMRARb6aLqgBO7yyAxTOQE2AKb9GfXnEo760AUcUmFx3ibVJJAzGytlQcNXd" crossorigin="anonymous"></script>
    <script src="resources/js/feather.min.js"></script>
    <script src="resources/js/Chart.min.js"></script>
    <script src="resources/js/dashboard.js"></script>
</body>

</html>