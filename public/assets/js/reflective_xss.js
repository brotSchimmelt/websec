/*
* JavaScript for the reflective XSS challenge.
*
* Displays a modal to enter the challenge solution (session cookie) if a JS 
* dialog function was used.
*/

/**
 * temporarily override the alert() function.
 */
(function () {
    var _alertXSS = window.alert;
    window.alert = function () {
        // run code BEFORE alert
        _alertXSS.apply(window, arguments);
        // run code AFTER alert
        $('#xss-solution').modal('show');
    };
})();

/**
 * temporarily override the prompt() function.
 */
(function () {
    var _promptXSS = window.prompt;
    window.prompt = function () {
        // run code BEFORE prompt
        _promptXSS.apply(window, arguments);
        // run code AFTER prompt
        $('#xss-solution').modal('show');
    };
})();

/**
 * temporarily override the confirm() function.
 */
(function () {
    var _confirmXSS = window.confirm;
    window.confirm = function () {
        // run code BEFORE confirm
        _confirmXSS.apply(window, arguments);
        // run code AFTER confirm
        $('#xss-solution').modal('show');
    };
})();
