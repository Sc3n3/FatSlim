<?php namespace Sc3n3\FatSlim\Cache;

class ArrayCache implements CacheInterface {

	private $container = array();

	public function get($key) {

		return isset($this->container[$key]) && !$this->isExpire($key) ? $this->container[$key]['content'] : false;
	}

	public function set($key, $val, $expire) {

		$val = array('expire' => $expire, 'time' => time(), 'content' => $val);
		return $this->container[$key] = $val;
	}

	public function has($key) {

		return isset($this->container[$key]);
	}

	public function del($key) {

		unset($this->container[$key]);
		return true;
	}

	private function isExpire($key) {

		$val = $this->container[$key];
		return ( $val['expire'] > 0 && $val['time'] + $val['expire'] < time() );
	}
}
