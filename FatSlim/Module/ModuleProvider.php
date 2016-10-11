<?php namespace Sc3n3\FatSlim\Module;

class ModuleProvider {

	protected $dir = null;
	protected $app = null;
	protected $prefix = null;

	public function __construct() {

		$this->app = app();	
	}

	public function setDir($class) {

		$info = new \ReflectionClass($class);

		return $this->dir = dirname($info->getFileName());
	}

	public function setViewPrefix($prefix) {

		return $this->prefix = $prefix;

	}

	public function boot() {

		ModuleService::getTemplateLoader()->addPath($this->dir .'/Views', $this->prefix);

		require $this->dir .'/routes.php';

	}

}