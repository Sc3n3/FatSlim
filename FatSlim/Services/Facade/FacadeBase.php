<?php namespace Sc3n3\FatSlim\Services\Facade;

use Statical\BaseProxy;

class FacadeBase extends BaseProxy {

	public static $instance = null;

	public static function __callStatic($name, $arguments) {

		if( !method_exists(static::_getProxyItem(), $name) ) {
			throw new \RuntimeException(basename(static::class) .'::'. $name .' method is not exist!');	
		}

		return call_user_func_array(array(static::_getProxyItem(), $name), $arguments);
    }
}