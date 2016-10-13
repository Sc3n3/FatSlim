<?php namespace Sc3n3\FatSlim\Services\Module;

use Sc3n3\FatSlim\Services\View\ViewService;

class ModuleService {

	private $moduleList = array();

	public function setList($list) {

		$this->moduleList = (array) $list;

		return $this;
	}

	public function runModules() {

		foreach( $this->moduleList as $moduleClass ) {

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

		return $this->applyRoutes();
	}

	private function getClassDir($class) {

		return dirname($class->getFileName());
	}

	private function getDirName($dir) {

		return mb_strtolower(basename($dir));
	}

	private function boot($object) {

		ViewService::addNamespace($object->getViewPrefix(), $object->getViewPath());
		require $object->getRoutes();

		return $this;
	}

	private function applyRoutes() {

		foreach(\Route::getRoutes() as $closure) {

			call_user_func($closure, app());

		}

		return $this;
	}
}