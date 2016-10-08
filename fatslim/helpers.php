<?php

function getApp() {

	return \Sc3n3\FatSlim\Bootstrap::getApp();
}

function path($path) {
	
	return realpath(\Sc3n3\FatSlim\Bootstrap::getPath() .'/../../'. $path);
}

function request() {

	return getApp()->request;
}

function response() {

	return getApp()->response;
}

function view() {

	return getApp()->view;
}

function url($url) {

	return $url;
}

function asset($url) {

	return '/assets/'. $url;
}

function route($route, $params = array()) {

	return getApp()->urlFor($route, $params);
}

function redirect($url, $code = '301') {

	return getApp()->redirect($url, $code);
}

function redirectFor($route, $params = array(), $code = '301') {

	return getApp()->redirect(route($route, $params), $code);
}

function fetch($view, $data = array(), $code = '200') {

	return getApp()->view->fetch($view, $data, $code);
}

function render($view, $data = array(), $code = '200') {

	return getApp()->render($view, $data, $code);
}

function stop() {

	return getApp()->stop();
}

function pass() {

	return getApp()->pass();
}

function halt($code = '403') {

	return getApp()->halt($code);
}

function renderHalt($view, $data = array(), $code = '403') {

	return render($view, $data, $code);
}

function fetchHalt($view, $data = array(), $code = '403') {

	return fetch($view, $data, $code);
}