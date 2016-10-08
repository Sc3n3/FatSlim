<?php

require '../vendor/autoload.php';

$app = new Sc3n3\FatSlim\Bootstrap();
$app->addMiddleware(new \Slim\Extras\Middleware\CsrfGuard);
//$app->addMiddleware(new \Slim\Extras\Middleware\HttpBasicAuth('username', 'password'));
$app->run();

exit;

$app = new \Slim\Slim();
$app->view()->setTemplatesDirectory('./app/View');
$app->view()->appendData(array('baseUrl' => 'http://localhost/slim/arabazz/'));

//$app->hook('slim.before', function() use ($app){
if(false !== $CntFiles = glob('./app/Controller/*.php')) foreach($CntFiles as $File) include $File;
//});




$app->run();