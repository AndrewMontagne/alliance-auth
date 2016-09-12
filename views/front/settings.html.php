<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?=ALLIANCE_NAME?> - Login</title>
    <link href="<?=getVersionedAsset('css/front/settings.css');?>" rel="stylesheet" type="text/css" />
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" rel="stylesheet" type="text/css" />
    <link href="<?=getVersionedAsset('css/bootstrap-flat.min.css');?>" rel="stylesheet" type="text/css" />
    <link href="<?=getVersionedAsset('css/bootstrap-flat-extras.min.css');?>" rel="stylesheet" type="text/css" />
    <script src="<?=getVersionedAsset('js/jquery-1.12.3.min.js');?>"></script>
    <script src="<?=getVersionedAsset('js/bootstrap.min.js');?>"></script>
    <script>
        $(function () {
            $('[data-toggle="tooltip"]').tooltip()
        })
    </script>
</head>
<body>
    <div id="box-container">
        <div id="box" class="container" role="main">
            <h3>Alliance Authentication Portal</h3>
            <ul class="nav nav-tabs">
                <li role="presentation" class="active"><a href="#">Characters</a></li>
                <li role="presentation"><a href="#">Groups</a></li>
                <li role="presentation"><a href="#">Apps</a></li>
                <li role="presentation"><a href="#">Settings</a></li>
            </ul>
            <div class="tab-content">
                <div class="table-container ">
                    <table class="table table-striped table-hover" style="margin: 0px;">
                        <thead>
                        <tr>
                            <th colspan="2">Name</th>
                            <th>Corporation</th>
                            <th>Alliance</th>
                            <th>Tools</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php if(!is_array($characters) || count($characters) < 1): ?>
                            <tr><td colspan="5"><i>No Characters On File</i></td></tr>
                        <?php else: foreach($characters as $character): $isPrimaryCharacter = $character->getCharacterId() === $primaryCharacterId;?>
                            <tr>
                            <td style="width: 32px">
                                <img class="table-portrait" src="http://image.eveonline.com/Character/<?=$character->getCharacterId();?>_32.jpg">
                            </td>
                            <td>
                                <?php if($isPrimaryCharacter) { ?>
                                    <b><?=$character->getCharacterName();?></b>
                                <?php } else { ?>
                                    <?=$character->getCharacterName();?>
                                <?php } ?>
                            </td>
                            <td>-</td>
                            <td>-</td>
                            <td>
                                <?php if(!$isPrimaryCharacter): ?>
                                    <span data-toggle="tooltip" data-placement="top" title="Make Primary" class="glyphicon glyphicon-star-empty clickable" onclick="alert('lel')"></span>&nbsp;
                                    <span data-toggle="tooltip" data-placement="top" title="Remove" class="glyphicon glyphicon-remove clickable" onclick="alert('lel')"></span>&nbsp;
                                <?php endif; ?>
                                <span data-toggle="tooltip" data-placement="top" title="Refresh Auth" class="glyphicon glyphicon-refresh clickable" onclick="alert('lel')"></span>
                            </td>
                        </tr><?php endforeach; endif;?>
                        </tbody>
                    </table>
                </div>
            </div>
            <div style="padding: 16px" class="row">
                <div class="col-xs-4" style="text-align: left !important; padding: 0px">
                        <span class="btn btn-default btn-sm">
                            <span class="glyphicon glyphicon-plus"></span> Add Character
                        </span>
                </div>
                <div class="col-xs-4" style="padding: 0px">
                        <span class="btn btn-primary btn-sm" disabled data-toggle="tooltip" data-placement="top" title="Add A Character To Continue">
                            Continue to <b><?="Application"?></b>
                        </span>
                </div>
                <div class="col-xs-4" style="text-align: right !important; padding: 0px">
                        <span class="btn btn-default btn-sm" onclick="location.href = '/logout';">
                            <span class="glyphicon glyphicon-log-out"></span> Log Out
                        </span>
                </div>
            </div>
        </div>
    </div>
</body>
</html>