<?php
require("$_SERVER[DOCUMENT_ROOT]/../src/dashboard_header.php");
$here = basename($_SERVER['PHP_SELF'], ".php");
?>

<!doctype html>
<html lang="en">



<body>
    <div class="container-fluid">
        <div class="row">
            <?php include("$_SERVER[DOCUMENT_ROOT]/../src/sidebar.php"); ?>

            <main role="main" class="col-md-9 ml-sm-auto col-lg-10 px-md-4">

                <div class="jumbotron shadow-sm">
                    <h1>Dashboard</h1>
                    <p>Insert Tables and Charts with user data.</p>
                </div>
                <div class="row">
                    <div class="col">
                        <div class="card shadow-sm">
                            <div class="card-header">Number of active users</div>
                            <div class="card-body">Some Number</div>
                        </div>
                    </div>
                    <div class="col">
                        <div class="card shadow-sm">
                            <div class="card-header">Total progress of registered students</div>
                            <div class="card-body">Some Chart</div>
                        </div>
                    </div>
                    <div class="col">
                        <div class="card shadow-sm">
                            <div class="card-header">Current level of the callenges</div>
                            <div class="card-body">Bachelor/Master etc.</div>
                        </div>
                    </div>
                </div>
                <br><br>
                <div class="row">
                    <div class="col">
                        <div class="card shadow-sm">
                            <div class="card-header">Students behind schedule</div>
                            <div class="card-body">List of Mail Addresses</div>
                        </div>
                    </div>
                </div>


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