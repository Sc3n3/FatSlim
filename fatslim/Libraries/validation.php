<?php

use Illuminate\Validation\Factory as ValidatorFactory;
use Symfony\Component\Translation\Translator;
use Symfony\Component\Translation\MessageSelector;

class Validator {

	private static $instance = null;

	public static function __callStatic($name, $arguments) {

		if ( !self::$instance ) {

    		self::$instance = new ValidatorFactory(new Translator('en_US', new MessageSelector()));

		}

		return call_user_func_array(array(self::$instance, $name), $arguments);

	}

}
