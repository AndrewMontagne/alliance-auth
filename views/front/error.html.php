<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?=$errorTitle?></title>
    <link href="<?=getVersionedAsset('css/front/error.css');?>" rel="stylesheet" type="text/css" />
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" rel="stylesheet" type="text/css" />
    <link href="<?=getVersionedAsset('css/bootstrap-flat.min.css');?>" rel="stylesheet" type="text/css" />
    <link href="<?=getVersionedAsset('css/bootstrap-flat-extras.min.css');?>" rel="stylesheet" type="text/css" />
    <script src="<?=getVersionedAsset('js/jquery-1.12.3.min.js');?>"></script>
    <script src="<?=getVersionedAsset('js/bootstrap.min.js');?>"></script>
    <script src="<?=getVersionedAsset('js/login.js');?>"></script>
</head>
<body>
    <div id="error-box-container">
        <div id="error-box" class="container" role="main">
            <div id="error-motif"></div>
            <h3 class="bold"><?=$errorTitle?></h3>
            <p style="font-family: monospace; margin-top: 20px">
                <b><?=$errorMessage;?></b><br/>
                Request ID: <?php global $requestID; echo $requestID;?>
            </p>
        </div>
    </div>
</body>
</html>