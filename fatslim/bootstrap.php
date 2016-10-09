<?php namespace Sc3n3\FatSlim;

class Bootstrap {

	public $slim = null;
	protected $path = null;

	public function __construct() {
		
		$this->slim = new \Slim\Slim();
		$this->path = self::getPath();
	}

	public static function getApp() {

		return \Slim\Slim::getInstance();
	}

	public static function getPath() {

		return realpath(__DIR__ .'/../');
	}

	public function addMiddleware($object) {

		return $this->slim->add($object);
	}

	public function run() {

		$this->setConfig();
		$this->setDatabase();
		$this->setCache();
		$this->setRoutes();

		$this->sessionStart();

		return $this->slim->run();
	}

	private function setConfig() {

		$this->slim->config( require( $this->path .'/app/config.php') );
		$this->slim->config('templates.path', $this->path .'/app/Views');
		$this->slim->config('cache_dir', realpath($this->path .'/cache'));

		$this->slim->config('view', new \Slim\Views\Twig());
		$this->slim->view->parserOptions = array(
			'debug' => $this->slim->config('debug'),
			'cache' => $this->slim->config('cache_dir')
		);
	}

	private function setRoutes() {

		require $this->path .'/app/routes.php';
	}

	private function setDatabase() {

		$active = $this->slim->config('database')['active'];
		$drivers = $this->slim->config('database')['drivers'];

		if ( !$active ) {
			return;
		}

		$manager = new \Illuminate\Database\Capsule\Manager;
		$manager->addConnection($drivers[$active]);

		$manager->setAsGlobal();
		$manager->bootEloquent();
	}

	private function setCache() {

		$active = $this->slim->config('cache')['active'];
		$drivers = $this->slim->config('cache')['drivers'];

		try {

			switch ($active) {
				case 'file':
					\Cache::setInstance(new \Sc3n3\FatSlim\Cache\FileCache($drivers['file']));
					break;

				case 'redis':
					$redis = new \Sc3n3\FatSlim\Connectors\RedisConnector($drivers['redis']);

					if ( !$client = $redis->connect() ) {
						throw new \Exception('Default Cache');
					}

					\Cache::setInstance(new \Sc3n3\FatSlim\Cache\RedisCache($client));
					break;

				default:
					throw new \Exception('Default Cache');
					break;
			}

		} catch(\Exception $e) {

			\Cache::setInstance(new \Sc3n3\FatSlim\Cache\ArrayCache);

		}
	}

	private function sessionStart() {

		$active = $this->slim->config('session')['active'];
		$drivers = $this->slim->config('session')['drivers'];

		switch ($active) {
			case 'cookie':
				$this->slim->add(new \Slim\Middleware\SessionCookie($drivers['cookie']));
				break;
			
			default:
				session_cache_limiter(false);
				session_start();
				break;
		}
	}
}