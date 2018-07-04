<!doctype html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="libs/bootstrap.min.css">
    <link rel="stylesheet" href="libs/bootstrap.min.css.map">
    <link rel="stylesheet" href="libs/fontawesome-all.min.css">
    <link rel="stylesheet" href="css/main.css">
    <title>CMS Lite!</title>
</head>
<body>
<div class="lightbox"><div class="loader_img"></div></div>
<div class="content">
    <?php if(\core\CmsLite::$app->user->isLoggedIn()) { ?>
        <nav class="navbar navbar-default cms-nav" role="navigation">
            <div class="container-fluid">
                <!-- Brand and toggle get grouped for better mobile display -->
                <div class="navbar-header">
                    <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
                        <span class="sr-only">Toggle navigation</span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                    </button>
                    <a class="navbar-brand" href="#">CMS Lite</a>
                </div>
                <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
                    <ul class="nav navbar-nav navbar-right">
                        <li><a href="<?=\core\Url::to('logout')?>"><?=\core\CmsLite::$app->user->getLogin()?>(выйти)</a></li>
                    </ul>
                </div>
            </div>
        </nav>
    <?php } ?>
    <?=$content?>
</div>

<!-- Modal -->
<div class="modal fade" id="modal-demo-meta-tags" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="myModalLabel">Предварительный просмотр мета-тегов</h4>
            </div>
            <div class="modal-body">
                <pre><code></code></pre>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Закрыть</button>
            </div>
        </div>
    </div>
</div>

<script src="libs/jquery.min.js"></script>
<script src="libs/bootstrap.min.js"></script>
<script src="js/note.js"></script>
<script src="js/main.js"></script>
</body>
</html>