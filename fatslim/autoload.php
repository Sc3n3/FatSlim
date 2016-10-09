<?php



$files = glob(__DIR__ ."/Libraries/*.[pP][hH][pP]");

foreach($files ? $files : array() as $file) {

	require $file;

}