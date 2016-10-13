<?php namespace Sc3n3\FatSlim\Services\Cache\Drivers;

class ArrayDriver implements CacheDriverInterface {

	private $container = array();

	public function get($key) {

		return isset($this->container[$key]) && !$this->isExpire($key) ? $this->container[$key]['content'] : false;
	}

	public function set($key, $val, $expire) {

		$val = array('expire' => $expire, 'time' => time(), 'content' => $val);
		return $this->container[$key] = $val;
	}

	public function has($key) {

		return ( isset($this->container[$key]) && !$this->isExpire($key) );
	}

	public function del($key) {

		unset($this->container[$key]);
		return true;
	}

	public function flush() {

		$this->container = array();
		return true;
	}

	private function isExpire($key) {

		$val = $this->container[$key];
		return ( $val['expire'] > 0 && $val['time'] + $val['expire'] < time() );
	}
}
