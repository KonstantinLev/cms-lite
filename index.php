<?php

require_once __DIR__.DIRECTORY_SEPARATOR.'admin'. DIRECTORY_SEPARATOR .'core'. DIRECTORY_SEPARATOR .'cms_autoload.php';

$cms = new core\CmsLite();

?>

<!doctype html>
<html lang="en">
<head>
    <?=$cms->registerMetaTags?>
    <!-- Latest compiled and minified CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
    <!-- Optional theme -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap-theme.min.css" integrity="sha384-rHyoN1iRsVXV4nD0JutlnGaslCJuC7uwjduW9SVrLvRYooPp2bWYgmgJQIXwl/Sp" crossorigin="anonymous">
    <link rel="shortcut icon" href="<?=$cms->getFavicon()?>" type="image/x-icon">
    <link rel="stylesheet" href="css/main.css">
    <title><?=$cms->title?></title>
</head>
<body>
    <section>
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <h1 class="text-center">CMS Lite base template</h1>

                    <ul>
                        <li>title - <?=$cms->get('title')?></li>
                        <li>meta tags - <pre><code><?=htmlspecialchars($cms->registerMetaTags)?></code></pre></li>
                        <li>phone - <?=$cms->get('phone')?></li>
                        <li>email - <?=$cms->get('email')?></li>
                        <li>Robots.txt - <pre><code><?=htmlspecialchars($cms->getRobotsTxt())?></code></pre></li>
                        <li>Metrics - <pre><code><?=htmlspecialchars($cms->getMetrics())?></code></pre></li>
                    </ul>
                </div>
            </div>
        </div>
    </section>


<!-- Latest compiled and minified JavaScript -->
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>
</body>
</html>