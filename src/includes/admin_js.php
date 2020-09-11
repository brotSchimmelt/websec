<script>
    // Search Script
    $(document).ready(function() {
        $("#user-search").on("keyup", function() {
            var value = $(this).val().toLowerCase();
            $("#user-table tr").filter(function() {
                $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
            });
        });
    });
</script>