<?php namespace Sc3n3\FatSlim\Cache;

class RedisCache implements CacheInterface {

	private $connection = null;

	public function __construct( $connection ) {

		$this->connection = $connection;
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

		return $this->connection->flush();;
	}
}