<?php namespace Sc3n3\FatSlim\Module;

class ModuleService {

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

}