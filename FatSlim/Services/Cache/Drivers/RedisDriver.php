<?php namespace Sc3n3\FatSlim\Services\Cache\Drivers;

use Sc3n3\FatSlim\Connectors\RedisConnector;

class RedisDriver implements CacheDriverInterface {

	private $connection = null;

	public function __construct( $config = array() ) {

		$redis = new RedisConnector($config);

		if ( !$client = $redis->connect() ) {
			throw new \Exception('Connection Problem!');
		}

		$this->connection = $client;
	}

	public function get($key) {

		return $this->connection->get($key);
	}

	public function set($key, $val, $expire) {

		return ( $this->connection->set($key, $val) && $this->connection->expire($key, $expire) );
	}

	public function has($key) {

		return $this->connection->exists($key);
	}

	public function del($key) {

		return $this->connection->del($key);
	}

	public function flush() {

		return $this->connection->flushdb();;
	}
}