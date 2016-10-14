<?php namespace Sc3n3\FatSlim\Services\Facade\Facades;

use Sc3n3\FatSlim\Services\Facade\FacadeBase;

class App extends FacadeBase {

	public static function _getProxyItem() {

		return parent::$instance;
	}
}