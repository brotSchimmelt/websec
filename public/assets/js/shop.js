/**
 * Refresh the current page.
 */
function RefreshPage() {

    if (window.location.search) {
        window.location.search += '&reload=true';
    } else {
        window.location.search += '?reload=true';
    }
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

