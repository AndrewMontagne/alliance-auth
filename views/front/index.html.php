<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>AUTH DUDE</title>
    <link href="<?=getVersionedAsset('css/front/login.css');?>" rel="stylesheet" type="text/css" />
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" rel="stylesheet" type="text/css" />
    <link href="<?=getVersionedAsset('css/bootstrap-flat.min.css');?>" rel="stylesheet" type="text/css" />
    <link href="<?=getVersionedAsset('css/bootstrap-flat-extras.min.css');?>" rel="stylesheet" type="text/css" />
    <script src="<?=getVersionedAsset('js/jquery-2.2.3.min.js');?>"></script>
    <script src="<?=getVersionedAsset('js/bootstrap.min.js');?>"></script>
    <script type="text/javascript">
        function handleLogin() {
            return false;
        }
    </script>
</head>
<body>
    <div id="login-box-container">
        <div id="login-box" class="container" role="main">
            <img style="margin-top: 20px;" src="https://image.eveonline.com/Alliance/<?=ALLIANCE_ID?>_128.png">
            <div class="caption">
                <h3 id="alliance-name" class="bold"><?=ALLIANCE_NAME?></h3>
                <form onsubmit="return handleLogin(this);" action="/login">
                    <div id="login-prompt">Please enter your login credentials:</div>
                    <p>
                        <input type="text" class="form-control" placeholder="Username">
                    </p>
                    <p>
                        <input type="password" class="form-control" placeholder="Password">
                    </p>
                    <p>
                        <button id="login-button" type="submit" class="btn btn-primary btn-lg btn-block">AUTHENTICATE</button>
                    </p>
                    <p>
                        <a href="/register" class="btn btn-default btn-sm bottom left">Register Account</a>
                        <a href="/recover-password" class="btn btn-default btn-sm bottom right">Reset Password</a>
                    </p>
                </form>
            </div>
        </div>
    </div>
</body>
</html>