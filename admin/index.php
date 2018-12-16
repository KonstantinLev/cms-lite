<?php

require_once __DIR__.DIRECTORY_SEPARATOR.'core'.DIRECTORY_SEPARATOR.'cms_autoload.php';

$pages = ['main' => 'Главная страница', 'about' => 'Страница "О нас"'];

$app = new core\CmsLite($pages);
$app->setPathFavicon(__DIR__.DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.'img');//todo without filename
$app->setPathRobots(__DIR__.DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.'robots.txt');

$app->run();