<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?=ALLIANCE_NAME?> - Register</title>
    <link href="<?=getVersionedAsset('css/front/register.css');?>" rel="stylesheet" type="text/css" />
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" rel="stylesheet" type="text/css" />
    <link href="<?=getVersionedAsset('css/bootstrap-flat.min.css');?>" rel="stylesheet" type="text/css" />
    <link href="<?=getVersionedAsset('css/bootstrap-flat-extras.min.css');?>" rel="stylesheet" type="text/css" />
    <script src="<?=getVersionedAsset('js/jquery-1.12.3.min.js');?>"></script>
    <script src="<?=getVersionedAsset('js/bootstrap.min.js');?>"></script>
    <script src="<?=getVersionedAsset('js/register.js');?>"></script>
</head>
<body>
    <div id="register-box-container">
        <div id="register-box" class="container" role="main">
            <div class="caption">
                <h3 id="alliance-name" class="bold">Register Account</h3>
                <form action="/register" onsubmit="return handleRegister(this);">
                    <div id="register-prompt">Please fill in the form:</div>
                    <p>
                        <label for="form-username">Username:</label>
                        <input id="form-username" type="text" class="form-control" placeholder="Desired Username" value="<?=$suggestedUsername?>">
                    </p>
                    <p>
                        <label for="form-password">Password:</label>
                        <input id="form-password" type="password" class="form-control" placeholder="Desired Password">
                    </p>
                    <p>
                        <label for="form-eve-character">Primary Character:</label>
                        <input id="form-eve-character" type="text" class="form-control" value="<?=$characterName?>" readonly>
                    </p>
                    <p>
                        <button id="register-button" type="submit" class="btn btn-primary btn-lg btn-block">COMPLETE REGISTRATION</button>
                    </p>
                </form>
            </div>
        </div>
    </div>
</body>
</html>