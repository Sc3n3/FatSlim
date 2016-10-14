<?php namespace Sc3n3\FatSlim;

use Slim\Slim;
//use Sc3n3\FatSlim\Services\App\AppService;

class Bootstrap extends Slim {

	private $time = null;

	public function __construct($config = array()) {
		
		$this->time = microtime(true);

		parent::__construct();

		$this->setLibraries();
		$this->setConfig($config);
		$this->runServices();
		$this->setRoutes();
	}

	public function __destruct() {

		if( $this->config('debug') ) {
			
			Services\View\ViewService::addNamespace('debug', __DIR__ .'/Views/Debug');

			$debug = array(
				'exec_time' => number_format( (microtime(true) - $this->time), 3 ) .' sec',
				'peak_memory' =>  round(memory_get_peak_usage() / 1024) .' KB',
				'query_log' => array_map(function($v){ $v['time'] = $v['time'] / 1000 .' ms'; return $v; }, $this->db->getQueryLog())
			);

			$this->render('@debug/debug', array('___debugProfile___' => $debug));
		}
	}

	public static function getInstance($name = 'default') {

		return Slim::getInstance($name);
	}

	public static function path($path = '') {

		return realpath(__DIR__ .'/../'. $path);
	}

	private function runServices() {

		
		$services = (array) $this->config('services');
		
		foreach($services as $service) {
			
			if (!class_exists($service)) {
				throw new \RuntimeException("Service not found!");
			}

			new $service($this);
		}
	}

	private function setConfig($config) {

		class_alias('Sc3n3\\FatSlim\\BaseController', 'BaseController');
		class_alias('Sc3n3\\FatSlim\\BaseController', 'App\\Controllers\\BaseController');

		if ( is_file($this->path('app/config.php')) ) {
			$config = array_merge(require($this->path('app/config.php')), $config);
		}

		$this->config($config);
		$this->config('cache_dir', $this->path('/cache'));
	}

	private function setRoutes() {

		$routeFiles = array('__default__' => $this->path('app/routes.php'));
		if(isset($this->moduleRoutes) && is_array($this->moduleRoutes)) {
			$routeFiles += $this->moduleRoutes;
		}

		foreach($routeFiles as $file) {
			is_file($file) ? require $file : '';
		}
	}

	private function setLibraries() {

		$files = glob(__DIR__ ."/Libraries/*.php");
		foreach($files ? $files : array() as $file) {
			require $file;
		}
	}
}