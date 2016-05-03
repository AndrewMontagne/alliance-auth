<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>AUTH DUDE</title>
    <link href="/css/front/login.css" rel="stylesheet" type="text/css" />
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" rel="stylesheet" type="text/css" />
    <link href="/css/bootstrap-flat.min.css" rel="stylesheet" type="text/css" />
    <link href="/css/bootstrap-flat-extras.min.css" rel="stylesheet" type="text/css" />
    <script src="/js/jquery-2.2.3.min.js"></script>
    <script src="/js/bootstrap.min.js"></script>
</head>
<body>
    <div id="login-box-container">
        <div id="login-box" class="container" role="main">
            <img style="margin-top: 20px;" src="https://image.eveonline.com/Alliance/<?=ALLIANCE_ID?>_128.png">
            <div class="caption">
                <h3 class="bold"><?=ALLIANCE_NAME?></h3>
                <form onsubmit="alert('submitted!'); return false;" action="/login">
                    <div id="login-prompt">Please enter your login credentials:</div>
                    <p>
                        <input type="email" class="form-control" placeholder="Email Address" required>
                    </p>
                    <p>
                        <input type="password" class="form-control" placeholder="Password" required>
                    </p>
                    <p>
                        <button type="button" class="btn btn-primary btn-lg btn-block">AUTHENTICATE</button>
                    </p>
                    <p>
                        <button type="button" class="btn btn-default btn-sm bottom left">Register Account</button>
                        <button type="button" class="btn btn-default btn-sm bottom right">Password Recovery</button>
                    </p>
                </form>
            </div>
        </div>
    </div>
</body>
</html>