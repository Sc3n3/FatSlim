<?php

Route::group('/admin', function() {

	Route::get('/', 'Sc3n3\FatSlim\Modules\Admin\Controllers\IndexController:getIndex')->name('admin::index');

});

