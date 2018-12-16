<?php

/* @var $cms core\CmsLite */

use core\Url;

$pages = $this->pages;


?>

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
                        <form id="main-form" action="<?=Url::to('ajax')?>" method="post">
                            <div class="cl-form-group">
                                <label class="cl-label" for="title">Название сайта</label>
                                <input type="text" placeholder="example: My Awesome Site" class="cl-input cl-field" name="title" id="title" value="<?=$cms->get('title')?>">
                            </div>
                            <div class="cl-form-group">
                                <label class="cl-label" for="phone">Номер телефона</label>
                                <input type="text" placeholder="example: 8(888)888-88-88" class="cl-input cl-field" name="phone" id="phone" value="<?=$cms->get('phone')?>">
                            </div>
                            <div class="cl-form-group">
                                <label class="cl-label" for="email">E-mail адрес для получения уведомлений с сайта</label>
                                <input type="text" placeholder="example: my_email@mail.ru" class="cl-input cl-field" name="email" id="email" value="<?=$cms->get('email')?>">
                            </div>
                            <button type="button" class="btn btn-main" data-action="save-main-tab" onclick="saveData(this);">Сохранить</button>
                        </form>
                    </div>
                    <div id="panel2" class="tab-pane fade">
                        <form id="meta-tag-form" action="<?=Url::to('ajax')?>" method="post">
                            <?php if(!empty($pages)) { ?>
                                <div class="row">
                                    <div class="col-md-5">
                                        <select name="select-page" id="" class="form-control select-page">
                                            <option value="">Выберите страницу...</option>
                                            <?php foreach ($pages as $key => $page) { ?>
                                                <option value="<?=$key?>"><?=$page?></option>
                                            <?php } ?>
                                        </select>
                                    </div>
                                </div>
                                <hr>
                            <?php } ?>
                            <div class="<?=!empty($pages) ? 'select-page-container' : ''?>">
                                <h3 class="current-page">Настройка тегов для страницы: <b></b></h3>
                                <hr>
                                <div class="row">
                                    <div class="col-md-5"><label class="cl-label" for="">Название мета-тега</label></div>
                                    <div class="col-md-5"><label class="cl-label" for="">Значение мета-тега</label></div>
                                </div>
                                <div class="block-meta">
                                    <?=$cms->drawMetaBlocks('meta_tags')?>
                                </div>

                                <div class="row">
                                    <div class="col-md-10 text-center">
                                        <a href="#" class="meta-plus" onclick="addMetaBlock(this, 'meta_tags');"><i class="fal fa-plus-circle"></i></a>
                                    </div>
                                </div>

                                <button type="button" class="btn btn-main" data-action="save-tags" data-type="meta_tags" onclick="saveTags(this);">Сохранить</button>
                                <button type="button" class="btn btn-default launch-demo-meta-tag">Демо</button>
                                <button type="button" class="btn btn-default cancel-change" data-link="<?=Url::to('ajax')?>">Отмена</button>
                            </div>

                        </form>
                    </div>
                    <div id="panel3" class="tab-pane fade">
                        <form action="<?=Url::to('ajax')?>">
                            <div class="row">
                                <div class="col-md-12">
                                    <p>Введите код счетчика Google Analytics или Яндекс.Метрики, чтобы отслеживать статистику вашего сайта</p>
                                    <div class="cl-form-group">
                                        <textarea class="cl-textarea cl-field" name="metrics" id="" cols="6" rows="10" ><?=$cms->getMetrics()?></textarea>
                                    </div>
                                    <button type="button" class="btn btn-main" data-action="save-metrics" onclick="saveData(this);">Сохранить</button>
                                </div>
                            </div>
                        </form>
                    </div>
                    <div id="panel4" class="tab-pane fade">
                        <p>Загрузите изображение favicon для сайта</p>
                        <form action="<?=Url::to('ajax')?>" enctype="multipart/form-data">
                            <div class="form-group">
                                <div class="wrap-file center">
                                    Нажмите здесь, чтобы прикрепить файл
                                    <input type="file" name="file" class="cl-field" id="file">
                                </div>
                                <p class="nameFile"></p>
<!--                                <p class="help-block">Подсказка</p>-->
                            </div>
                            <div class="preview-ico">Текущий favicon - <img src="<?=Url::to('img/favicon.ico?'.time())?>" alt=""></div>
                            <button type="button" class="btn btn-main" data-action="save-ico" onclick="uploadICO(this);">Сохранить</button>
                        </form>
                    </div>
                    <div id="panel5" class="tab-pane fade">
                        <form action="<?=Url::to('ajax')?>">
                            <div class="row">
                                <div class="col-md-12">
                                    <p>Редактирование файла <i>Robots.txt</i></p>
                                    <div class="cl-form-group">
                                        <textarea class="cl-textarea cl-field" name="robots" id="" cols="6" rows="10" ><?=$cms->getRobotsTxt()?></textarea>
                                    </div>
                                    <button type="button" class="btn btn-main" data-action="save-robots" onclick="saveData(this);">Сохранить</button>
                                </div>
                            </div>
                        </form>
                    </div>
                    <div id="panel6" class="tab-pane fade">
                        <form id="og-tag-form" action="<?=Url::to('ajax')?>" method="post">
                            <?php if(!empty($pages)) { ?>
                                <div class="row">
                                    <div class="col-md-5">
                                        <select name="select-page" id="" class="form-control select-page">
                                            <option value="">Выберите страницу...</option>
                                            <?php foreach ($pages as $key => $page) { ?>
                                                <option value="<?=$key?>"><?=$page?></option>
                                            <?php } ?>
                                        </select>
                                    </div>
                                </div>
                                <hr>
                            <?php } ?>
                            <div class="<?=!empty($pages) ? 'select-page-container' : ''?>">
                                <h3 class="current-page">Настройка тегов для страницы: <b></b></h3>
                                <hr>
                                <div class="row">
                                    <div class="col-md-5"><label class="cl-label" for="">Название мета-тега</label></div>
                                    <div class="col-md-5"><label class="cl-label" for="">Значение мета-тега</label></div>
                                </div>
                                <div class="block-meta">
                                    <?=$cms->drawMetaBlocks('og_tags')?>
                                </div>

                                <div class="row">
                                    <div class="col-md-10 text-center">
                                        <a href="#" class="meta-plus" onclick="addMetaBlock(this, 'og_tags');"><i class="fal fa-plus-circle"></i></a>
                                    </div>
                                </div>
                                <button type="button" class="btn btn-main" data-action="save-tags" data-type="og_tags" onclick="saveTags(this);">Сохранить</button>
                                <button type="button" class="btn btn-default launch-demo-meta-tag">Демо</button>
                                <button type="button" class="btn btn-default cancel-change" data-link="<?=Url::to('ajax')?>">Отмена</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>