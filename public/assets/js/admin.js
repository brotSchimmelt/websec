// search for user on keyup
$(document).ready(function () {
    $("#user-search").on("keyup", function () {
        var value = $(this).val().toLowerCase();
        $("#user-table tr").filter(function () {
            $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
        });
    });
});
