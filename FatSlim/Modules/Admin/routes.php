<?php

Route::set(function($app) {

	$app->group('/admin', function() use($app) {

		$app->get('/', 'Sc3n3\FatSlim\Modules\Admin\Controllers\IndexController:getIndex')->name('admin::index');

	});

});


