<?php

use Illuminate\Database\Schema\Builder;

class Schema {

	public static function __callStatic($name, $arguments) {

		return call_user_func_array(array(\DB::getInstance()->schema(), $name), $arguments);

	}

	protected function __clone() {}

}