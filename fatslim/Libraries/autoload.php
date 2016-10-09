<?php

$files = array_values(preg_grep('/^((?!autoload.php).)*$/i', glob(__DIR__ ."/*.[pP][hH][pP]")));

foreach($files as $file) {

	require $file;

}