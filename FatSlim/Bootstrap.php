<?php namespace Sc3n3\FatSlim;

class Bootstrap {

	public $slim = null;
	private $time = null;
	protected $path = null;

	public function __construct($config = array()) {
		
		$this->time = microtime(true);
		$this->slim = new \Slim\Slim($config);
		$this->path = self::getPath();
	}

	public function __destruct() {

		if( $this->slim->config('debug') ) {
			
			Services\View\ViewService::addNamespace('debug', __DIR__ .'/Views/Debug');
			
			$debug = array(
				'exec_time' => number_format( (microtime(true) - $this->time), 3 ) .' sec',
				'peak_memory' =>  round(memory_get_peak_usage() / 1024) .' KB',
				'query_log' => array_map(function($v){ $v['time'] = $v['time'] / 1000 .' ms'; return $v; }, \DB::getQueryLog())
			);

			$this->slim->render('@debug/debug', array('___debugProfile___' => $debug));
		}
	}

	public function __call($name, $arguments) {

		return call_user_func_array(array($this->slim, $name), $arguments);
	}

	public function __get($name) {

		return $this->slim->$name;
	}

	public static function getApp($appName = 'default') {

		return \Slim\Slim::getInstance($appName);
	}

	public static function getPath() {

		return realpath(__DIR__ .'/../');
	}

	public static function getLoader() {

		$files = glob(__DIR__ ."/Libraries/*.php");
		foreach($files ? $files : array() as $file) {
			require $file;
		}
	}

	public function addMiddleware($object) {

		return $this->slim->add($object);
	}

	public function run() {

		$this->setConfig();
		$this->setDatabase();
		$this->setCache();
		$this->setModules();

		$this->sessionStart();
		
		return $this->slim->run();
	}

	private function setModules() {

		$moduleService = new Services\Module\ModuleService;
		$moduleService->setModuleList($this->slim->config('modules'))->runModules();

		\Route::apply();
	}

	private function setConfig() {

		$this->slim->config( require( $this->path .'/app/config.php') );
		$this->slim->config('templates.path', $this->path .'/app/Views');
		$this->slim->config('cache_dir', realpath($this->path .'/cache'));

		$this->slim->config('view', Services\View\ViewService::getEngine());
		$this->slim->view->parserOptions = array(
			'debug' => $this->slim->config('debug'),
			'cache' => $this->slim->config('cache_dir') .'/view'
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

		\DB::setInstance($manager);

		if ( $this->slim->config('debug') ) {
			$manager->getConnection()->enableQueryLog();
		}
	}

	private function setCache() {

		$active = $this->slim->config('cache')['active'];
		$drivers = $this->slim->config('cache')['drivers'];

		\Cache::setPrefix($this->slim->config('cache')['prefix']);

		try {

			switch ($active) {
				case 'file':
					\Cache::setInstance(new Services\Cache\FileCache($drivers['file']));
					break;

				case 'redis':
					$redis = new Connectors\RedisConnector($drivers['redis']);

					if ( !$client = $redis->connect() ) {
						throw new \Exception('Default Cache');
					}

					\Cache::setInstance(new Services\Cache\RedisCache($client));
					break;

				default:
					throw new \Exception('Default Cache');
					break;
			}

		} catch(\Exception $e) {

			\Cache::setInstance(new Services\Cache\ArrayCache);

		}
	}

	private function sessionStart() {

		$active = $this->slim->config('session')['active'];
		$drivers = $this->slim->config('session')['drivers'];

		session_cache_limiter(false);

		try {

			switch ($active) {
				case 'cookie':
					$this->slim->add(new \Slim\Middleware\SessionCookie($drivers['cookie']));
					break;
				
				case 'redis':
					$redis = new Connectors\RedisConnector($drivers['redis']);

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
					session_start();
					break;

				default:
					throw new \Exception('Default Session');
					break;
			}

		} catch(\Exception $e) {

			ini_set('session.gc_maxlifetime', $drivers['native']['maxlifetime']);
			session_start();

		}
	}

}