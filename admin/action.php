<?php

require_once 'core/CmsLite.php';
use cms\CmsLite;

$cms = new CmsLite();
echo $cms->ajax($_POST['action'], $_POST['data']);