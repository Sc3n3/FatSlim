<?php namespace Sc3n3\FatSlim\Modules\Admin;

use Sc3n3\FatSlim\Services\Module\ModuleProvider;
use Sc3n3\FatSlim\Services\Module\ModuleProviderInterface;

class AdminProvider extends ModuleProvider {

	public function register() {
		
		$this->setDir(__DIR__);
		$this->setViewPrefix('admin');
		$this->setViewPath(__DIR__ .'/Views');
		$this->setRoutes(__DIR__ .'/routes.php');
		
	}

}