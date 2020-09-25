(function () {
    var _old_alert = window.alert;
    window.alert = function () {
        // run some code BEFORE alert
        _old_alert.apply(window, arguments);
        // run some code AFTER alert
        $('#xss-solution').modal('show');
    };
})();

(function () {
    var _old_prompt = window.prompt;
    window.prompt = function () {
        // run some code BEFORE alert
        _old_prompt.apply(window, arguments);
        // run some code AFTER alert
        $('#xss-solution').modal('show');
    };
})();

(function () {
    var _old_confirm = window.confirm;
    window.confirm = function () {
        // run some code BEFORE alert
        _old_confirm.apply(window, arguments);
        // run some code AFTER alert
        $('#xss-solution').modal('show');
    };
})();

