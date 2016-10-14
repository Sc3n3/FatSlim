<?php namespace Sc3n3\FatSlim\Services\Facade;

use Statical\Manager;

class FacadeService {

	public function __construct($instance) {
		
		FacadeBase::$instance = $instance;

		$manager = new Manager();
		$manager->addProxySelf('*');
		$manager->addNamespace('*', '*');

		$facades = (array) $instance->config('aliases');
		foreach($facades as $alias => $facade) {
			$manager->addProxyInstance($alias, $facade, function() use ($facade) {
				return $facade::_getProxyItem();
			});
		}
/*
		$manager->addProxyInstance('Cache', 'Sc3n3\\FatSlim\\Services\\Facade\\Facades\\Cache', function(){ return \Sc3n3\FatSlim\Services\Facade\Facades\Cache::_getProxyItem(); });
		$manager->addProxyInstance('Route', 'Sc3n3\\FatSlim\\Services\\Facade\\Facades\\Route', function(){ return \Sc3n3\FatSlim\Services\Facade\Facades\Route::_getProxyItem(); });
		$manager->addProxyInstance('View', 'Sc3n3\\FatSlim\\Services\\Facade\\Facades\\View', function(){ return \Sc3n3\FatSlim\Services\Facade\Facades\View::_getProxyItem(); });
		$manager->addProxyInstance('DB', 'Sc3n3\\FatSlim\\Services\\Facade\\Facades\\DB', function(){ return \Sc3n3\FatSlim\Services\Facade\Facades\DB::_getProxyItem(); });
*/		//dd(View::fetch('index.slim.php'));
	}

}