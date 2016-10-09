<?php

Route::set(function($app) {

	$app->get('/', 'App\Controllers\IndexController:getIndex')->name('index::main');

	$app->group('/test', function() use($app) {

		$app->get('/cache', 'App\Controllers\IndexController:getCacheTest')->name('test::cache');
		$app->get('/model', 'App\Controllers\IndexController:getModelTest')->name('test::model');

	});

});


