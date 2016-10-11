<?php

class DB {

	private static $instance = null;

	public static function __callStatic($name, $arguments) {

		return call_user_func_array(array(self::$instance->getConnection(), $name), $arguments);
	}

	public static function setInstance($instance) {

		return self::$instance = $instance;
	}

	public static function getInstance() {

		return self::$instance;
	}

	protected function __clone() {}

}