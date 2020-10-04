// wrap the original dialog functions
const AlertXSS = window.alert;
const PromptXSS = window.prompt;
const ConfirmXSS = window.confirm;

// show a successful response
function showSuccess(message, response) {

    // correct solution detected
    if (response != 1 && response != 0) {

        // ask user to set challenge cookie now or later
        if (ConfirmXSS(response)) {
            var decryptedCookie = atob(challengeCookie);
            document.cookie = "XSS_STOLEN_SESSION=" + decryptedCookie + ";path=/";
            window.location.reload();
            return null;
        } else {
            return null;
        };
    } else {
        // suppress alert() to avoid spamming the user screen
        if (response == 0) {
            return null;
        }

        // dialog found but without payload
        var userMessage;
        if (message.includes("XSS_YOUR_SESSION")) {
            userMessage = "document.cookie";
        } else {
            userMessage = message;
        }
        return AlertXSS("Sorry, no attack detected. Check the instructions " +
            "again!\nYour input was: " + userMessage);
    }
}

// show post error
function showError(message, response) {
    console.log("The request could not be processed!");
    console.log("This was the message: " + message);
    console.log("This was the response: " + response);
    return AlertXSS("This should not have happened :/ Please report this " +
        "error to the Learnweb forum.");
}

// override alert()
// BAD PRACTICE: Never do this anywhere else!
window.alert = function (message) {
    var request;
    request = $.post("xss_form_handler.php", {
        storedXSSMessage: message
    });
    request.done(function (response) {
        showSuccess(message, response);
    });
    request.fail(function (response) {
        showError(message, response);
    });
};

// override prompt()
// BAD PRACTICE: Never do this anywhere else!
window.prompt = function (message) {
    var request;
    request = $.post("xss_form_handler.php", {
        storedXSSMessage: message
    });
    request.done(function (response) {
        showSuccess(message, response);
    });
    request.fail(function (response) {
        showError(message, response);
    });
};

// override confirm()
// BAD PRACTICE: Never do this anywhere else!
window.confirm = function (message) {
    var request;
    request = $.post("xss_form_handler.php", {
        storedXSSMessage: message
    });
    request.done(function (response) {
        showSuccess(message, response);
    });
    request.fail(function (response) {
        showError(message, response);
    });
};