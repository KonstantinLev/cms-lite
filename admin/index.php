<?php

require_once 'core/CmsLite.php';
require_once 'core/View.php';
use cms\CmsLite;
use cms\View;

/**
 * @var CmsLite
 */
$cms = new CmsLite();
$view = new View();
$view->set('cms', $cms);
$view->display('main');