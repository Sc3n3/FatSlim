<?php namespace Sc3n3\FatSlim\Services\Facade\Facades;

use Sc3n3\FatSlim\Services\Facade\FacadeBase;

class Config extends FacadeBase
{
	public static function get($key)
	{
		return parent::$instance->config($key);
	}

	public static function set($key, $value)
	{
		return parent::$instance->config($key, $value);
	}

	public static function _getProxyItem() {

		return self;
	}
}
