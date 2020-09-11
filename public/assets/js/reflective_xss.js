(function () {
    var _old_alert = window.alert;
    window.alert = function () {
        // run some code BEFORE alert
        _old_alert.apply(window, arguments);
        // run some code AFTER alert
        $('#xss-solution').modal('show');
    };
})();
