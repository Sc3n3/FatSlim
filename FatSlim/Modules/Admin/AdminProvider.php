<?php namespace Sc3n3\FatSlim\Modules\Admin;

use Sc3n3\FatSlim\Core\Module\ModuleProvider;
use Sc3n3\FatSlim\Core\Module\ModuleProviderInterface;

class AdminProvider extends ModuleProvider implements ModuleProviderInterface {

	public function register() {
		
		$this->setDir(__DIR__);
		$this->setViewPrefix('admin');
		$this->setViewPath(__DIR__ .'/Views');
		$this->setRoutes(__DIR__ .'/routes.php');
		
	}

}