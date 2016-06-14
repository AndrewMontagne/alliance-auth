function showErrorMessage(errorMessage) {
    $("#authorize-prompt")
        .addClass("error-prompt")
        .removeClass("success-prompt")
        .html(errorMessage);
}

function showSuccessMessage(message) {
    $("#authorize-prompt")
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

function handleAuthSuccess(data) {
    showSuccessMessage(data.message);
    $("#login-button").prop("disabled", true);
    setTimeout(function() {
        location.href = data.redirectUri;
    }, 1000);
}

function handleAuthFailure(data) {
    showErrorMessage(data.responseJSON.message);
    shake();
}

$(function () {
    $('[data-toggle="tooltip"]').tooltip()
})

function handleGrantAuthorization() {
    var csrf_token = $("#csrf_token").val();
    var client_id = $("#client_id").val();

    $.ajax("/oauth/authorize", {
        "method": "POST",
        "data": {
            client_id,
            csrf_token
        },
        "success": handleAuthSuccess,
        "error": handleAuthFailure
    });
}