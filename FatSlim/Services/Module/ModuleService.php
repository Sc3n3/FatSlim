<?php namespace Sc3n3\FatSlim\Services\Module;

use Sc3n3\FatSlim\Services\View\ViewService;

class ModuleService {

	private $instance = null; 

	public function __construct($instance) {

		$this->instance = $instance;
		$this->instance->moduleRoutes = array();

		$modules = (array) $instance->config('modules');

		foreach( $modules as $moduleClass ) {

			$moduleProvider = new $moduleClass;
			
			if( !$moduleProvider instanceof ModuleProvider ) {
				continue;
			}

			$details = new \ReflectionClass($moduleProvider);
			$moduleDir = $this->getClassDir($details);

			$moduleProvider->setDir($moduleDir);
			$moduleProvider->setViewPrefix($this->getDirName($moduleDir));
			$moduleProvider->setViewPath($moduleDir .'/Views');
			$moduleProvider->setRoutes($moduleDir .'/routes.php');

			$moduleProvider->register();

			$this->boot($moduleProvider);
		}
	}

	private function getClassDir($class) {

		return dirname($class->getFileName());
	}

	private function getDirName($dir) {

		return mb_strtolower(basename($dir));
	}

	private function boot($object) {

		ViewService::addNamespace($object->getViewPrefix(), $object->getViewPath());
		$this->instance->moduleRoutes += array($object->getViewPrefix() => $object->getRoutes());
	}

}