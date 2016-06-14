<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?=ALLIANCE_NAME?> - Authorize</title>
    <link href="<?=getVersionedAsset('css/front/authorize.css');?>" rel="stylesheet" type="text/css" />
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" rel="stylesheet" type="text/css" />
    <link href="<?=getVersionedAsset('css/bootstrap-flat.min.css');?>" rel="stylesheet" type="text/css" />
    <link href="<?=getVersionedAsset('css/bootstrap-flat-extras.min.css');?>" rel="stylesheet" type="text/css" />
    <script src="<?=getVersionedAsset('js/jquery-1.12.3.min.js');?>"></script>
    <script src="<?=getVersionedAsset('js/bootstrap.min.js');?>"></script>
    <script src="<?=getVersionedAsset('js/authorize.js');?>"></script>
    <?php if (isset(\Auth\Session::current()->redirectPath)) : ?>
    <script type="text/javascript">window.redirectURI = "<?=\Auth\Session::current()->redirectPath;?>";</script>
    <?php endif ?>
</head>
<body>
    <div id="authorize-box-container">
        <div id="authorize-box" class="container" role="main">
            <div class="caption">
                <h3 id="alliance-name" class="bold">Authorize Application</h3>
                <input type="hidden" id="csrf_token" value="<?=$csrfToken;?>"/>
                <input type="hidden" id="client_id" value="<?=$clientId;?>"/>
                <div id="authorize-prompt">
                    "<b><?=$application->getRemarks();?>"</b>
                    <?php if ($application->getIsOfficial()): ?>
                    <span class="glyphicon glyphicon-ok-sign" style="color: deepskyblue" data-toggle="tooltip" data-placement="top" title="Official Application"></span>
                    <?php endif ?>
                    wants to:</div>
                <div class="scrollable-div well well-sm">
                    <ul>
                        <?php if ($application->canAuth):?><li>Verify your identity</li><?php endif ?>
                        <?php if ($application->canPing):?><li>Send you Slack pings</li><?php endif ?>
                        <li>Take your first-born child</li>
                    </ul>
                </div>
                <p>
                    <button onclick="handleGrantAuthorization()" class="btn btn-success btn-sm bottom left">AUTHORIZE</button>
                    <button onclick="history.back()" class="btn btn-danger btn-sm bottom right">CANCEL</button>
                </p>
            </div>
        </div>
    </div>
</body>
</html>