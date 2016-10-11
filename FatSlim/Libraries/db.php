<?php

class DB {

	private static $instance = null;

	public static function __callStatic($name, $arguments) {

		return call_user_func_array(array(self::$instance, $name), $arguments);
	}

	public static function setInstance($instance) {

		return self::$instance = $instance;
	}
}