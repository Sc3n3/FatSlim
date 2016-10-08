<?php namespace Sc3n3\FatSlim;

use Illuminate\Database\Capsule\Manager;

class Bootstrap {

	public $app = null;
	public $path = __DIR__;

	public function __construct() {
		
		$this->app = new \Slim\Slim();
	}

	public static function getApp() {

		return \Slim\Slim::getInstance();
	}

	public function addMiddleware($object) {

		return $this->app->add($object);
	}

	public function run() {

		$this->setConfig();
		$this->setConnections();
		$this->setHelpers();
		$this->setRoutes();

		$this->sessionStart();

		return $this->app->run();
	}

	private function setConfig() {

		$this->app->config( require( $this->path .'/config.php') );
		$this->app->config('templates.path', $this->path .'/Views');
		$this->app->config('cache', realpath($this->path .'/../cache'));

		$this->app->config('view', new \Slim\Views\Twig());
		$this->app->view->parserOptions = array(
			'debug' => $this->app->config('debug'),
			'cache' => $this->app->config('cache')
		);
	}

	private function setRoutes() {

		require $this->path .'/routes.php';
	}

	private function setHelpers() {

		require $this->path .'/helpers.php';
	}

	private function setConnections() {

		$active = $this->app->config('database')['active'];
		$drivers = $this->app->config('database')['drivers'];

		$manager = new Manager;
		$manager->addConnection($drivers[$active]);

		$manager->setAsGlobal();
		$manager->bootEloquent();
	}

	private function sessionStart() {

		$active = $this->app->config('session')['active'];
		$drivers = $this->app->config('session')['drivers'];

		switch ($active) {
			case 'cookie':
				$this->app->add(new \Slim\Middleware\SessionCookie($drivers['cookie']));
				break;
			
			default:
				session_cache_limiter(false);
				session_start();
				break;
		}

		return;		
	}
}