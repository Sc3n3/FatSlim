<?php namespace Sc3n3\FatSlim\Services\Module;

use Sc3n3\FatSlim\Services\View\ViewService;

class ModuleService {

	private $moduleList = array();

	public function setList(array $list) {

		$this->moduleList = $list;

		return $this;
	}

	public function runModules() {

		foreach( $this->moduleList as $module ) {

			$details = new \ReflectionClass($module);
			
			if( !$details->implementsInterface('\Sc3n3\FatSlim\Services\Module\ModuleProviderInterface') ) {
				continue;
			}

			$moduleDir = $this->getClassDir($details);

			$class = new $module;

			$class->setDir($moduleDir);
			$class->setViewPrefix($this->getClassName($moduleDir));
			$class->setViewPath($moduleDir .'/Views');
			$class->setRoutes($moduleDir .'/routes.php');

			$class->register();

			$this->boot($class);
		}

		return $this->applyRoutes();
	}

	private function getClassDir($class) {

		return dirname($class->getFileName());
	}

	private function getClassName($dir) {

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