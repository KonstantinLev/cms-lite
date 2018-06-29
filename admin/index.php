<?php

require_once 'CmsLite.php';
use cms\CmsLite;

//TODO избавиться от пути
$file = 'config.json';
/**
 * @var CmsLite
 */
$cms = new CmsLite($file);

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
    <div class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <h1 class="title"><span>Настройка сайта</span></h1>
                </div>
            </div>
            <div class="row">
                <div class="cl-tab-container">
                    <div class="col-md-3">
                        <ul class="cl-tab-nav">
                            <li class="active"><a data-toggle="tab" href="#panel1"><i class="fal fa-home"></i><span>Главное</span></a></li>
                            <li><a data-toggle="tab" href="#panel2"><i class="fal fa-users-cog"></i><span>Мета-теги</span></a></li>
                            <li><a data-toggle="tab" href="#panel6"><i class="fal fa-share-square"></i><span>Open Graph</span></a></li>
                            <li><a data-toggle="tab" href="#panel3"><i class="fal fa-chart-bar"></i><span>Метрики</span></a></li>
                            <li><a data-toggle="tab" href="#panel4"><i class="fal fa-star"></i><span>Favicon</span></a></li>
                            <li><a data-toggle="tab" href="#panel5"><i class="fal fa-robot"></i><span>Robots.txt</span></a></li>
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
                                    <button type="button" class="btn btn-main" onclick="saveMainTab();">Сохранить</button>
                                </form>
                            </div>
                            <div id="panel2" class="tab-pane fade">
                                <form id="meta-tag-form" action="action.php" method="post">
                                    <div class="row">
                                        <div class="col-md-5"><label class="cl-label" for="">Название мета-тега</label></div>
                                        <div class="col-md-5"><label class="cl-label" for="">Значение мета-тега</label></div>
                                    </div>
                                    <div class="block-meta">
                                        <?php if ($cms->getMetaTags()) { ?>
                                            <?php foreach ($cms->getMetaTags() as $metaName => $metaVal) { ?>
                                                <div class="block-meta-item">
                                                    <div class="row">
                                                        <input type="hidden" name="type" value="meta_tags">
                                                        <div class="col-md-5">
                                                            <div class="cl-form-group">
                                                                <input type="text" placeholder="example: charset" class="cl-input" name="name" value="<?=$metaName?>">
                                                            </div>
                                                        </div>
                                                        <div class="col-md-5">
                                                            <div class="cl-form-group">
                                                                <input type="text" placeholder="example: UTF-8" class="cl-input" name="value" value="<?=$metaVal?>">
                                                            </div>
                                                        </div>
                                                        <div class="col-md-2">
                                                            <a href="#" class="meta-times" onclick="removeMetaBlock(this);"><i class="fal fa-times-circle"></i></a>
                                                        </div>
                                                    </div>
                                                </div>
                                            <?php } ?>
                                        <?php } else { ?>
                                            <div class="block-meta-item">
                                                <div class="row">
                                                    <input type="hidden" name="type" value="meta_tags">
                                                    <div class="col-md-5">
                                                        <div class="cl-form-group">
                                                            <input type="text" placeholder="example: charset" class="cl-input" name="name">
                                                        </div>
                                                    </div>
                                                    <div class="col-md-5">
                                                        <div class="cl-form-group">
                                                            <input type="text" placeholder="example: UTF-8" class="cl-input" name="value">
                                                        </div>
                                                    </div>
                                                    <div class="col-md-2">
                                                        <a href="#" class="meta-times" onclick="removeMetaBlock(this);"><i class="fal fa-times-circle"></i></a>
                                                    </div>
                                                </div>
                                            </div>
                                        <?php }  ?>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-10 text-center">
                                            <a href="#" class="meta-plus" onclick="addMetaBlock('meta_tags');"><i class="fal fa-plus-circle"></i></a>
                                        </div>
                                    </div>

                                    <button type="button" class="btn btn-main" onclick="saveMetaTagTab();">Сохранить</button>
                                    <button type="button" class="btn btn-default launch-demo-meta-tag">Демо</button>
                                </form>
                            </div>
                            <div id="panel3" class="tab-pane fade">
                                <h2>Метрики</h2>
                            </div>
                            <div id="panel4" class="tab-pane fade">
                                <h2>Favicon</h2>
                            </div>
                            <div id="panel5" class="tab-pane fade">
                                <h2>Robots.txt</h2>
                            </div>
                            <div id="panel6" class="tab-pane fade">
                                <form id="og-tag-form" action="action.php" method="post">
                                    <div class="row">
                                        <div class="col-md-5"><label class="cl-label" for="">Название мета-тега</label></div>
                                        <div class="col-md-5"><label class="cl-label" for="">Значение мета-тега</label></div>
                                    </div>
                                    <div class="block-meta">
                                        <?php if ($cms->getOGTags()) { ?>
                                            <?php foreach ($cms->getOGTags() as $metaName => $metaVal) { ?>
                                                <div class="block-meta-item">
                                                    <div class="row">
                                                        <input type="hidden" name="type" value="og_tags">
                                                        <div class="col-md-5">
                                                            <div class="cl-form-group">
                                                                <input type="text" placeholder="example: charset" class="cl-input" name="name" value="<?=$metaName?>">
                                                            </div>
                                                        </div>
                                                        <div class="col-md-5">
                                                            <div class="cl-form-group">
                                                                <input type="text" placeholder="example: UTF-8" class="cl-input" name="value" value="<?=$metaVal?>">
                                                            </div>
                                                        </div>
                                                        <div class="col-md-2">
                                                            <a href="#" class="meta-times" onclick="removeMetaBlock(this);"><i class="fal fa-times-circle"></i></a>
                                                        </div>
                                                    </div>
                                                </div>
                                            <?php } ?>
                                        <?php } else { ?>
                                            <div class="block-meta-item">
                                                <div class="row">
                                                    <input type="hidden" name="type" value="og_tags">
                                                    <div class="col-md-5">
                                                        <div class="cl-form-group">
                                                            <input type="text" placeholder="example: og:title" class="cl-input" name="name">
                                                        </div>
                                                    </div>
                                                    <div class="col-md-5">
                                                        <div class="cl-form-group">
                                                            <input type="text" placeholder="example: The Rock" class="cl-input" name="value">
                                                        </div>
                                                    </div>
                                                    <div class="col-md-2">
                                                        <a href="#" class="meta-times" onclick="removeMetaBlock(this);"><i class="fal fa-times-circle"></i></a>
                                                    </div>
                                                </div>
                                            </div>
                                        <?php }  ?>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-10 text-center">
                                            <a href="#" class="meta-plus" onclick="addMetaBlock('og_tags');"><i class="fal fa-plus-circle"></i></a>
                                        </div>
                                    </div>

                                    <button type="button" class="btn btn-main" onclick="saveOGTagTab();">Сохранить</button>
                                    <button type="button" class="btn btn-default launch-demo-og-tag">Демо</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
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