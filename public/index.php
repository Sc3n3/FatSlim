<?php

require '../vendor/autoload.php';

$app = new Sc3n3\FatSlim\Bootstrap();
$app->addMiddleware(new \Slim\Extras\Middleware\CsrfGuard);
//$app->addMiddleware(new \Slim\Extras\Middleware\HttpBasicAuth('username', 'password'));
$app->run();