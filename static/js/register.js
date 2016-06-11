function handleRegister() {
    var username = $('#form-username').val();
    var password = $('#form-password').val();

    if(username.length < 1) {
        showErrorMessage("Please Enter Your Desired Username");
        return false;
    }
    if(password.length < 1) {
        showErrorMessage("Please Enter Your Desired Password");
        return false;
    }

    $.ajax("/register/callback", {
        "method": "POST",
        "data": {
            "username" : username,
            "password" : password
        },
        "success": handleRegisterSuccess,
        "error": handleRegisterFailure
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
        $('#register-box').animate({
            'left': computed + 'px'
        }, 100);
    }
    $('#register-box').animate({
        'left': '-150px'
    }, 50);
}

function showErrorMessage(errorMessage) {
    $('#register-prompt')
        .addClass('error-prompt')
        .removeClass('success-prompt')
        .html(errorMessage);
}

function showSuccessMessage(message) {
    $('#register-prompt')
        .removeClass('error-prompt')
        .addClass('success-prompt')
        .html(message);
}

function handleRegisterSuccess(data) {
    showSuccessMessage(data.message);
    $('#register-button').prop('disabled', true);
    setTimeout(function() {
        location.href = data.redirect != undefined ? data.redirect : '/';
    }, 1000);
}

function handleRegisterFailure(data) {
    showErrorMessage(data.responseJSON.message);
    shake();
}