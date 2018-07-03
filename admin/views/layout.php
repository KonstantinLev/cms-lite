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
            <div class="modal-body"></div>
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