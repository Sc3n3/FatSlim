<?php

Route::get('/', 'App\Controllers\IndexController:getIndex')->name('index::main');

Route::group('/test', function() {

	Route::get('/cache', 'App\Controllers\IndexController:getCacheTest')->name('test::cache');
	Route::get('/model', 'App\Controllers\IndexController:getModelTest')->name('test::model');
	Route::get('/validator', 'App\Controllers\IndexController:getValidatorTest')->name('test::validator');

});
