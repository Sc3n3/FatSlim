<?php

class Route {

	private static $routes = array();

	public static function set($closure) {

		if( !is_callable($closure) ) {
			return false;
		}

		self::$routes[] = $closure;

		return true;
	}

	public static function apply() {

		foreach(self::$routes as $closure) {

			call_user_func($closure, app());

		}

		return true;
	}
}