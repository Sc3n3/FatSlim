<?php

function app() {

	return \Sc3n3\FatSlim\Bootstrap::getApp();
}

function path($path) {
	
	return realpath(\Sc3n3\FatSlim\Bootstrap::getPath() .'/'. $path);
}

function request() {

	return app()->request();
}

function response() {

	return app()->response();
}

function view() {

	return app()->view();
}

function url($url) {

	return $url;
}

function asset($url) {

	return '/assets/'. $url;
}

function route($route, $params = array()) {

	return app()->urlFor($route, $params);
}

function redirect($url, $code = '301') {

	return app()->redirect($url, $code);
}

function redirectFor($route, $params = array(), $code = '301') {

	return app()->redirect(route($route, $params), $code);
}

function fetch($view, $data = array(), $code = '200') {

	return app()->view->fetch($view, $data, $code);
}

function render($view, $data = array(), $code = '200') {

	return app()->render($view, $data, $code);
}

function stop() {

	return app()->stop();
}

function pass() {

	return app()->pass();
}

function halt($code = '403') {

	return app()->halt($code);
}

function renderHalt($view, $data = array(), $code = '403') {

	return render($view, $data, $code);
}

function fetchHalt($view, $data = array(), $code = '403') {

	return fetch($view, $data, $code);
}

function dump($var) {
	var_dump($var);
}

function predump($var) {
	echo "<pre>";
	print_r($var);
	echo '</pre>';
}

function predd($var) {
	echo "<pre>";
	print_r($var);
	exit('</pre>');
}