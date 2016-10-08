<?php namespace Sc3n3\FatSlim;

use Cache;
use Illuminate\Database\Capsule\Manager;
use Sc3n3\FatSlim\Connectors\RedisConnector;

class Bootstrap {

	public $app = null;
	protected $path = __DIR__;

	public function __construct() {
		
		$this->app = new \Slim\Slim();
	}

	public static function getApp() {

		return \Slim\Slim::getInstance();
	}

	public static function getPath() {

		return __DIR__;
	}

	public function addMiddleware($object) {

		return $this->app->add($object);
	}

	public function run() {

		$this->setHelpers();
		$this->setConfig();
		$this->setDatabase();
		$this->setCache();
		$this->setRoutes();

		$this->sessionStart();

		return $this->app->run();
	}

	private function setConfig() {

		$this->app->config( require( $this->path .'/../config.php') );
		$this->app->config('templates.path', $this->path .'/../Views');
		$this->app->config('cache_dir', realpath($this->path .'/../../cache'));

		$this->app->config('view', new \Slim\Views\Twig());
		$this->app->view->parserOptions = array(
			'debug' => $this->app->config('debug'),
			'cache' => $this->app->config('cache_dir')
		);
	}

	private function setRoutes() {

		require $this->path .'/../routes.php';
	}

	private function setHelpers() {

		require $this->path .'/helpers.php';
	}

	private function setDatabase() {

		$active = $this->app->config('database')['active'];
		$drivers = $this->app->config('database')['drivers'];

		if ( !$active ) {
			return;
		}

		$manager = new Manager;
		$manager->addConnection($drivers[$active]);

		$manager->setAsGlobal();
		$manager->bootEloquent();
	}

	private function setCache() {

		$active = $this->app->config('cache')['active'];
		$drivers = $this->app->config('cache')['drivers'];

		try {

			switch ($active) {
				case 'file':
					Cache::setInstance(new \Sc3n3\FatSlim\Cache\FileCache($drivers['file']));
					break;

				case 'redis':
					$redis = new RedisConnector($drivers['redis']);

					if ( !$client = $redis->connect() ) {
						throw new \Exception('Default Cache');
					}

					Cache::setInstance(new \Sc3n3\FatSlim\Cache\RedisCache($client));
					break;

				default:
					throw new \Exception('Default Cache');
					break;
			}

		} catch(\Exception $e) {

			Cache::setInstance(new \Sc3n3\FatSlim\Cache\ArrayCache);
		}
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
	}
}