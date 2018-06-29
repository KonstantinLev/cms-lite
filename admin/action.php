<?php

require_once 'CmsLite.php';
use cms\CmsLite;

$cms = new CmsLite();

var_dump($_POST);

return $cms->save($_POST['action'], $_POST['data']);