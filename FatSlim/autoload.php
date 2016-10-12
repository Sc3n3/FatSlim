<?php

$files = glob(__DIR__ ."/Core/Libraries/*.php");

foreach($files ? $files : array() as $file) {

	require $file;

}

is_file(path('app') .'/routes.php') ? require path('app') .'/routes.php' : '';
