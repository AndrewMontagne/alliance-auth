function showErrorMessage(errorMessage) {
    $("#login-prompt")
        .addClass("error-prompt")
        .removeClass("success-prompt")
        .html(errorMessage);
}

function showSuccessMessage(message) {
    $("#login-prompt")
        .removeClass("error-prompt")
        .addClass("success-prompt")
        .html(message);
}

function shake() {
    var l = 10;
    var original = -150;
    for( var i = 0; i < 8; i++ ) {
        var computed;
        if (i % 2 > 0.51) {
            computed = original - l;
        } else {
            computed = original + l;
        }
        $("#login-box").animate({
            "left": computed + "px"
        }, 100);
    }
    $("#login-box").animate({
        "left": "-150px"
    }, 50);
}

$(function () {
    $('[data-toggle="tooltip"]').tooltip()
})