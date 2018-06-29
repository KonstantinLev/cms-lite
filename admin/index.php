<?php

require_once 'CmsLite.php';
use cms\CmsLite;

/**
 * @var CmsLite
 */
$cms = new CmsLite();

?>

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
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <h1 class="title"><span>Site settings</span></h1>
            </div>
        </div>
        <div class="row">
            <div class="cl-tab-container">
                <div class="col-md-3">
                    <ul class="cl-tab-nav">
                        <li class="active"><a data-toggle="tab" href="#panel1"><i class="fal fa-home"></i><span>Главное</span></a></li>
                        <li><a data-toggle="tab" href="#panel2"><i class="fal fa-users-cog"></i><span>Базовые мета-теги</span></a></li>
                    </ul>
                </div>
                <div class="col-md-9">
                    <div class="tab-content">
                        <div id="panel1" class="tab-pane fade in active">
                            <form id="main-form" action="action.php" method="post">
                                <div class="cl-form-group">
                                    <label class="cl-label" for="title">Название сайта</label>
                                    <input type="text" placeholder="example: My Awesome Site" class="cl-input" name="title" id="title" value="<?=$cms->get('title')?>">
                                </div>
                                <div class="cl-form-group">
                                    <label class="cl-label" for="phone">Номер телефона</label>
                                    <input type="text" placeholder="example: 8(888)888-88-88" class="cl-input" name="phone" id="phone" value="<?=$cms->get('phone')?>">
                                </div>
                                <div class="cl-form-group">
                                    <label class="cl-label" for="email">E-mail адрес для получения уведомлений с сайта</label>
                                    <input type="text" placeholder="example: my_email@mail.ru" class="cl-input" name="email" id="email" value="<?=$cms->get('email')?>">
                                </div>
                                <button class="btn btn-default" onclick="saveMainTab();">Save change</button>
                            </form>
                        </div>
                        <div id="panel2" class="tab-pane fade">
                            <form action="action.php">
                                <div class="cl-form-group">
                                    <label class="cl-label" for="charset">Кодировка</label>
                                    <input type="text" placeholder="example: UTF-8" class="cl-input" id="charset" value="<?=$cms->get('charset')?>">
                                </div>
                                <div class="cl-form-group">
                                    <label class="cl-label" for="description">Описание страницы</label>
                                    <input type="text" placeholder="example: описание страницы" class="cl-input" id="description">
                                </div>
                                <div class="cl-form-group">
                                    <label class="cl-label" for="keywords">Ключевые слова</label>
                                    <input type="text" placeholder="example: ключевые слова через запяту" class="cl-input" id="keywords">
                                </div>
                                <button type="submit" class="btn btn-default">Save change</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>





        </div>
    </div>


    <script src="libs/jquery.min.js"></script>
    <script src="libs/bootstrap.min.js"></script>
    <script src="js/main.js"></script>
</body>
</html>
