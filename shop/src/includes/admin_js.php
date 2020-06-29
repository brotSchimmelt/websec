<!-- <script src="../assets/js/vendor/jquery-3.5.1.slim.min.js"></script> -->
<script src="../assets/js/vendor/jquery-3.5.1.min.js"></script>
<script src="../assets/js/vendor/bootstrap.bundle.min.js"></script>
<script src="../assets/js/vendor/feather.min.js"></script>
<script src="../assets/js/vendor/Chart.min.js"></script>
<script src="../assets/js/dashboard.js"></script>

<!-- Search Script -->
<script>
    $(document).ready(function() {
        $("#user-search").on("keyup", function() {
            var value = $(this).val().toLowerCase();
            $("#user-table tr").filter(function() {
                $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
            });
        });
    });
</script>