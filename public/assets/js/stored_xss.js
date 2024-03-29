/*
* JavaScript for the stored XSS challenge.
* 
* Checks any user dialog for a valid payload with a post request to 
* 'post_handler.php' and sets the challenge cookie.
* 
* Displays a success msg if a payload was found, an error msg if the user used 
* a JS dialog function without payload or nothing if the challenge cookie
* is already set.
*/


// wrap the original functions in constants
const AlertXSS = window.alert;
const PromptXSS = window.prompt;
const ConfirmXSS = window.confirm;
const WriteXSS = document.write;

/**
 * Sets challenge cookie and displays success msg to user.
 * 
 * @param {string} message The original message from the used dialog function.
 * @param {number or string} response from 'post_handler.php'.
 */
function showSuccess(message, response) {

    // correct solution detected
    if (response != 1 && response != 0) {

        // ask user to set challenge cookie now or later
        if (ConfirmXSS(response)) {

            // set challenge cookie
            var decryptedCookie = atob(challengeCookie);
            document.cookie = "XSS_STOLEN_SESSION=" + decryptedCookie + ";path=/";
            window.location.reload();

            // 'null', because a return value is expected, but not used
            return null;
        } else {
            // do nothing if user does not want to set the cookie
            return null;
        };
    } else {
        // suppress alert() to avoid spamming the users screen
        if (response == 0) {
            return null;
        }

        // JS dialog found but without a valid payload
        var userMessage;
        if (message.includes("XSS_YOUR_SESSION")) {

            // cookie
            var cookie = document.cookie;
            userMessage = message.replace(cookie, "document.cookie");

        } else {
            userMessage = message;
        }
        return AlertXSS("Sorry, no attack detected. Check the instructions " +
            "again!\nYour input was: " + userMessage);
    }
}

/**
 * Logs error to console and notifies user that an unexpected error occurred.
 * 
 * @param {string} message 
 * @param {number or string} response 
 */
function showError(message, response) {
    console.log("The request could not be processed!");
    console.log("This was the message: " + message);
    console.log("This was the response: " + response);
    return AlertXSS("This should not have happened :/ Please report this " +
        "error in the Learnweb forum.");
}


/*
* overwrite all JS dialog and write functions
*/

// overwrite document.write()
// BAD PRACTICE: Never do this anywhere else!
document.write = function (str) {
    alert(str);
};

// overwrite alert()
// BAD PRACTICE: Never do this anywhere else!
window.alert = function (message) {
    var request;
    request = $.post("post_handler.php", {
        storedXSSMessage: message
    });
    request.done(function (response) {
        showSuccess(message, response);
    });
    request.fail(function (response) {
        showError(message, response);
    });
};

// overwrite prompt()
// BAD PRACTICE: Never do this anywhere else!
window.prompt = function (message) {
    var request;
    request = $.post("post_handler.php", {
        storedXSSMessage: message
    });
    request.done(function (response) {
        showSuccess(message, response);
    });
    request.fail(function (response) {
        showError(message, response);
    });
};

// overwrite confirm()
// BAD PRACTICE: Never do this anywhere else!
window.confirm = function (message) {
    var request;
    request = $.post("post_handler.php", {
        storedXSSMessage: message
    });
    request.done(function (response) {
        showSuccess(message, response);
    });
    request.fail(function (response) {
        showError(message, response);
    });
};
