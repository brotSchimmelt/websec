// wrap the original functions
const AlertXSS = window.alert;
const PromptXSS = window.prompt;
const ConfirmXSS = window.confirm;

// override alert()
// BAD PRACTICE: Never do this anywhere else!
window.alert = function (message) {
    var request;
    request = $.post("xss_form_handler.php", {
        storedXSSMessage: message
    });
    request.done(function (response) {
        if (response != 1) {
            return AlertXSS(response);
        } else {
            var userMessage;
            if (message.includes("XSS_YOUR_SESSION")) {
                userMessage = "document.cookie";
            } else {
                userMessage = message;
            }
            return AlertXSS("Sorry, no attack detected. Check the instructions again!");
        }
    });
    request.fail(function (response) {
        console.log("The request could not be processed!");
        console.log("This was the message: " + message);
        console.log("This was the response: " + response);
        return AlertXSS("This should not have happened :/ Please report this error to the Learnweb forum.")
    });

};

// override prompt()
window.prompt = function (message) {
    var request;
    request = $.post("xss_form_handler.php", {
        storedXSSMessage: message
    });
    request.done(function (response) {
        if (response != 1) {
            return AlertXSS(response);
        } else {
            var userMessage;
            if (message.includes("XSS_YOUR_SESSION")) {
                userMessage = "document.cookie";
            } else {
                userMessage = message;
            }
            return AlertXSS("Sorry, no attack detected. Check the instructions again!");
        }
    });
    request.fail(function (response) {
        console.log("The request could not be processed!");
        console.log("This was the message: " + message);
        console.log("This was the response: " + response);
        return AlertXSS("This should not have happened :/ Please report this error to the Learnweb forum.")
    });

};

// override confirm()
window.confirm = function (message) {
    var request;
    request = $.post("xss_form_handler.php", {
        storedXSSMessage: message
    });
    request.done(function (response) {
        if (response != 1) {
            return AlertXSS(response);
        } else {
            var userMessage;
            if (message.includes("XSS_YOUR_SESSION")) {
                userMessage = "document.cookie";
            } else {
                userMessage = message;
            }
            return AlertXSS("Sorry, no attack detected. Check the instructions again!");
        }
    });
    request.fail(function (response) {
        console.log("The request could not be processed!");
        console.log("This was the message: " + message);
        console.log("This was the response: " + response);
        return AlertXSS("This should not have happened :/ Please report this error to the Learnweb forum. Error code: 061")
    });

};