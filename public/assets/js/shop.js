// refresh the page
function RefreshPage() {
    window.location.reload();
}
// initialize tooltips and popovers
$(document).ready(function () {
    $('[data-toggle="popover"]').popover();
});
$(document).ready(function () {
    $('[data-toggle="tooltip"]').tooltip();
});