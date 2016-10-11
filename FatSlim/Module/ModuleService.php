<?php namespace Sc3n3\FatSlim\Module;

class ModuleService {

	private static $templateInstance = null;

	public static function setModules() {

		$modules = app()->config('modules');
		foreach( $modules ? $modules : array() as $module ) {

			$details = new \ReflectionClass($module);
			if( $details->implementsInterface('\Sc3n3\FatSlim\Module\ModuleProviderInterface') ) {

				$class = new $details->name;
				$class->setDir($class);
				$class->register();
				$class->boot();
			}
		}

	}

	public static function setTemplateInstance(\Twig_Environment $instance) {

		return self::$templateInstance = $instance;
	}

	public static function getTemplateLoader() {

		return self::$templateInstance->getLoader();
	}

}