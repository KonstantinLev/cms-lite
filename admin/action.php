<?php

require_once 'CmsLite.php';
use cms\CmsLite;

//TODO избавиться от пути
$file = 'config.json';
$cms = new CmsLite($file);
echo $cms->ajax($_POST['action'], $_POST['data']);