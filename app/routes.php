<?php

$app = getApp();

$app->group('/', function() use($app) {
	
	$app->get('/test', 'App\Controllers\IndexController::getTest')->name('index::test');
	$app->get('/', 'App\Controllers\IndexController::getIndex')->name('index::main');
});
