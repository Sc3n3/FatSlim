<?php namespace Sc3n3\FatSlim\Services\Facade\Facades;

use Sc3n3\FatSlim\Services\Facade\FacadeBase;

class Input extends FacadeBase {

	public static function _getProxyItem() {

		return parent::$instance->request;
	}
	
	public static function file($name)
	{
		return isset($_FILES[$name]) && $_FILES[$name]['size'] ? $_FILES[$name] : null;
	}
}
