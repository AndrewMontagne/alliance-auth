function handleLogin() {
    var username = $('#form-username').val();
    var password = $('#form-password').val();

    if(username.length < 1) {
        showErrorMessage("Please Enter Your Username:");
        return false;
    }
    if(password.length < 1) {
        showErrorMessage("Please Enter Your Password:");
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
    var l = 25;
    for( var i = 0; i < 8; i++ ) {
        $('#login-box').animate({
            'left': "+=" + ( l = -l ) + 'px'
        }, 100);
    }
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
        location.href = data.redirectUri;
    }, 1000);
}

function handleLoginFailure(data) {
    showErrorMessage(data.responseJSON.message);
    shake();
}