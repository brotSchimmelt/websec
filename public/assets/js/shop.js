/**
 * Refresh the current page.
 */
function RefreshPage() {
    window.location.reload();
}

/**
 * Initialize popovers and tooltips.
 */
$(document).ready(function () {
    $('[data-toggle="popover"]').popover();
});
$(document).ready(function () {
    $('[data-toggle="tooltip"]').tooltip();
});

