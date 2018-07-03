<?php
//TODO изменить
require_once __DIR__.DIRECTORY_SEPARATOR.'core'.DIRECTORY_SEPARATOR.'cms_autoload.php';
use core\CmsLite;

$cms = new CmsLite();
echo $cms->ajax($_POST['action'], $_POST['data']);