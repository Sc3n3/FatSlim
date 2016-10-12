<?php namespace Sc3n3\FatSlim\Core\Module;

use Sc3n3\FatSlim\Core\Views\ViewService;

class ModuleService {

	private static $moduleList = array();

	public static function setModuleList(array $list) {

		return self::$moduleList = $list;
	}

	public static function runModules() {

		foreach( self::$moduleList as $module ) {

			$details = new \ReflectionClass($module);
			
			if( !$details->implementsInterface('\Sc3n3\FatSlim\Core\Module\ModuleProviderInterface') ) {
				continue;
			}

			$moduleDir = self::getClassDir($details);

			$class = new $module;

			$class->setDir($moduleDir);
			$class->setViewPrefix(self::getClassName($details));
			$class->setViewPath($moduleDir .'/Views');
			$class->setRoutes($moduleDir .'/routes.php');

			$class->register();

			self::boot($class);
		}
	}

	private static function getClassDir($class) {

		return dirname($class->getFileName());
	}

	private static function getClassName($class) {

		return mb_strtolower(basename(self::getClassDir($class)));
	}

	private static function boot($object) {

		ViewService::addNamespace($object->getViewPrefix(), $object->getViewPath());
		require $object->getRoutes();
	}

}