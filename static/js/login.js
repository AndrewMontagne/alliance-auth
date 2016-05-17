function handleLogin() {
    var username = $('#form-username').val();
    var password = $('#form-password').val();

    if(username.length < 1) {
        showErrorMessage("Please Enter Your Username");
        return false;
    }
    if(password.length < 1) {
        showErrorMessage("Please Enter Your Password");
        return false;
    }

    $.ajax("/login", {
        "method": "POST",
        "data": {
            "username" : username,
            "password" : password
        },
        "success": handleLoginSuccess,
        "error": handleLoginFailure
    });
    return false;
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
        $('#login-box').animate({
            'left': computed + 'px'
        }, 100);
    }
    $('#login-box').animate({
        'left': '-150px'
    }, 50);
}

function showErrorMessage(errorMessage) {
    $('#login-prompt')
        .addClass('error-prompt')
        .removeClass('success-prompt')
        .html(errorMessage);
}

function showSuccessMessage(message) {
    $('#login-prompt')
        .removeClass('error-prompt')
        .addClass('success-prompt')
        .html(message);
}

function handleLoginSuccess(data) {
    showSuccessMessage(data.message);
    $('#login-button').prop('disabled', true);
    setTimeout(function() {
        location.href = window.redirectURI;
    }, 1000);
}

function handleLoginFailure(data) {
    showErrorMessage(data.responseJSON.message);
    shake();
}