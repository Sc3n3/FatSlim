<?php namespace Sc3n3\FatSlim\Services\Session;

use \Slim\Middleware\SessionCookie;
use \Sc3n3\FatSlim\Connectors\RedisConnector;

class SessionService {

	public function __construct($instance) {

		$active = $instance->config('session')['active'];
		$drivers = $instance->config('session')['drivers'];

		session_cache_limiter(false);

		try {

			switch ($active) {
				case 'cookie':
					$instance->add(new SessionCookie($drivers['cookie']));
					break;
				
				case 'redis':
					$redis = new RedisConnector($drivers['redis']);

					if ( !$client = $redis->connect() ) {
						throw new \Exception('Default Session');
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