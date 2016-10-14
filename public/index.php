<?php

require '../vendor/autoload.php';

$app = new \Sc3n3\FatSlim\Bootstrap();
//$app->add(new \Slim\Extras\Middleware\HttpBasicAuth('username', 'password'));
$app->run();