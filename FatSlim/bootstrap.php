<?php namespace Sc3n3\FatSlim;

class Bootstrap {

	public $slim = null;
	protected $path = null;

	public function __construct() {
		
		$this->slim = new \Slim\Slim();
		$this->path = self::getPath();
	}

	public function __destruct() {

		$this->slim->config('debug') ? predump( \DB::getQueryLog() ) : null;
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

		$this->sessionStart();

		\Route::apply();

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

		\DB::setInstance($manager->getConnection());

		$this->slim->config('debug') ? $manager->getConnection()->enableQueryLog() : null;
	}

	private function setCache() {

		$active = $this->slim->config('cache')['active'];
		$drivers = $this->slim->config('cache')['drivers'];

		\Cache::setPrefix($this->slim->config('cache')['prefix']);

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

		try {

			switch ($active) {
				case 'cookie':
					$this->slim->add(new \Slim\Middleware\SessionCookie($drivers['cookie']));
					break;
				
				case 'redis':
					$redis = new \Sc3n3\FatSlim\Connectors\RedisConnector($drivers['redis']);

					if ( !$client = $redis->connect() ) {
						throw new \Exception('Default Cache');
					}
					
					$config = array('time' => $drivers['redis']['maxlifetime'], 'prefix' => $drivers['redis']['prefix']);

					$handlers = array();
					$handlers['open'] = function($savePath, $sessionName) { };
					$handlers['close'] = function() use($client) { $client = null; unset($client); };
					$handlers['read'] = function($id) use($client, $config) { $data = $client->get($config['prefix'].$id); $client->expire($config['prefix'].$id, $config['time']); return $data; };
					$handlers['write'] = function($id, $data) use($client, $config) { $client->set($config['prefix'].$id, $data); $client->expire($config['prefix'].$id, $config['time']); };
					$handlers['destroy'] = function($id) use($client, $config) { $client->del($config['prefix'].$id); };
					$handlers['gc'] = function($maxLifetime) { };

					session_set_save_handler($handlers['open'], $handlers['close'], $handlers['read'], $handlers['write'], $handlers['destroy'], $handlers['gc']);
					
					session_cache_limiter(false);
					session_start();
					break;

				default:
					throw new \Exception('Default Session');
					break;
			}

		} catch(\Exception $e) {

			ini_set('session.gc_maxlifetime', $drivers['native']['maxlifetime']);
			session_cache_limiter(false);
			session_start();

		}
	}

}