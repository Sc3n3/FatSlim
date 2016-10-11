<?php namespace Sc3n3\FatSlim\Cache;

interface CacheInterface {

	public function get($key);

	public function set($key, $val, $expire);

	public function has($key);
	
	public function del($key);

	public function flush();

}