<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>AUTH DUDE</title>
    <link href="/css/front/login.css" rel="stylesheet" type="text/css" />
    <link href="/css/bootstrap.min.css" rel="stylesheet" type="text/css" />
    <link href="/css/bootstrap-flat.min.css" rel="stylesheet" type="text/css" />
    <link href="/css/bootstrap-flat-extras.min.css" rel="stylesheet" type="text/css" />
    <script src="/js/jquery-2.2.3.min.js"></script>
    <script src="/js/bootstrap.min.js"></script>
</head>
<body>
    <div id="login-box-container">
        <div id="login-box" class="container" role="main">
            <img style="margin-top: 20px;" src="https://image.eveonline.com/Alliance/99005866_128.png">
            <div class="caption">
                <h3 class="bold">Just Let It Happen</h3>
                <hr/>
                <form onsubmit="alert('submitted!'); return false;" action="/login">
                    <p>
                        <input type="email" class="form-control" placeholder="Email Address" required>
                    </p>
                    <p>
                    <div class="input-group">
                        <input type="password" class="form-control" placeholder="Password" required>
                        <span class="input-group-btn">
                            <submit class="btn btn-primary btn-default" type="button">LOG IN</submit>
                        </span>
                    </div>
                    </p>
                    <p>
                        <button type="button" class="btn btn-default btn-sm">Register Account</button>
                        <button type="button" class="btn btn-default btn-sm">Password Recovery</button>
                    </p>
                </form>
                <hr/>
                <div class="centered" style="color: lightgray; font-size: small; margin-bottom: 16px">
                    &copy; <?=date("Y")?> Just Let It Happen
                </div>
            </div>
        </div>
    </div>
</body>
</html>