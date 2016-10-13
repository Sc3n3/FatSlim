<?php namespace Sc3n3\FatSlim\Services\View;

class ViewService {

	private static $instance = null;

	public static function getEngine() {

		if( self::$instance == null ) {

			$class = app()->config('template.engine');
			
			self::$instance = new $class;
		}
		
		return self::$instance;
	}

	public static function addNamespace($prefix, $path) {

		return self::$instance->addNamespace($prefix, $path);
	}
}
