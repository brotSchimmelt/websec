/*
* JavaScript for the reflective XSS challenge.
*
* Displays a modal to enter the challenge solution (session cookie) if a JS 
* dialog was used.
*/


// temporarily override alert()
(function () {
    var _alertXSS = window.alert;
    window.alert = function () {
        // run code BEFORE alert
        _alertXSS.apply(window, arguments);
        // run code AFTER alert
        $('#xss-solution').modal('show');
    };
})();

// temporarily override prompt()
(function () {
    var _promptXSS = window.prompt;
    window.prompt = function () {
        // run code BEFORE prompt
        _promptXSS.apply(window, arguments);
        // run code AFTER prompt
        $('#xss-solution').modal('show');
    };
})();

// temporarily override confirm()
(function () {
    var _confirmXSS = window.confirm;
    window.confirm = function () {
        // run code BEFORE confirm
        _confirmXSS.apply(window, arguments);
        // run code AFTER confirm
        $('#xss-solution').modal('show');
    };
})();

