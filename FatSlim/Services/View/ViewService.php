<?php namespace Sc3n3\FatSlim\Services\View;

class ViewService {

	private static $instance = null;

	public static function setEngine($class) {

		if( !$class instanceof \Slim\View ) {
			throw new \RuntimeException('Template Engine is not valid!');
		}

		return self::$instance = $class;
	}

	public static function getEngine() {

		return self::$instance;
	}

	public static function addNamespace($prefix, $path) {

		return self::$instance->addNamespace($prefix, $path);
	}
}
