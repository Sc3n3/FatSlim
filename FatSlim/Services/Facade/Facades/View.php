<?php namespace Sc3n3\FatSlim\Services\Facade\Facades;

use Sc3n3\FatSlim\Services\Facade\FacadeBase;

class View extends FacadeBase {

	public static function _getProxyItem() {

		return parent::$instance->view;
	}
}