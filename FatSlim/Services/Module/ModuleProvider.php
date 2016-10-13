<?php namespace Sc3n3\FatSlim\Services\Module;

class ModuleProvider {

	private $dir = null;
	private $app = null;
	private $prefix = null;
	private $routes = null;

	public function __construct() {

		$this->app = app();	
	}

	public function setDir($dir) {

		return $this->dir = $dir;
	}

	public function setViewPrefix($prefix) {

		return $this->prefix = $prefix;
	}

	public function setViewPath($path) {

		return $this->viewPath = $path;
	}

	public function setRoutes($file) {

		return $this->routes = $file;
	}

	public function getDir() {

		return $this->dir;
	}

	public function getViewPrefix() {

		return $this->prefix;
	}

	public function getViewPath() {

		return $this->viewPath;
	}

	public function getRoutes() {

		return $this->routes;
	}

}