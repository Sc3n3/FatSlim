<?php namespace Sc3n3\FatSlim\Cache;

class FileCache implements CacheInterface {

	private $config = array();

	public function __construct($config = array()) {

		$defaults = array(
			'path' => '../cache'
		);

		$this->config = array_merge($defaults, $config);
	}

	public function get($key) {

		return $this->isExpire($key);
	}

	public function set($key, $val, $expire) {

		$val = json_encode(array('expire' => $expire, 'time' => time(), 'content' => $val));
		return ( $file = $this->getFile($key, true) ) ? file_put_contents($file, $val) : false;
	}

	public function has($key) {

		return is_file($this->getFile($key)) && $this->isExpire($this->getFile($key));
	}

	public function del($key) {

		return $this->removeFile($key);
	}

	private function isExpire($key) {

		$val = $this->getContent($key);
		$valid = !$val || $val['expire'] > 0 && $val['time'] + $val['expire'] < time() ? false : $val['content'];

		if ( !$valid ) {
			$this->removeFile($key);
		}

		return $valid;
	}

	private function getContent($key) {

		$val = @file_get_contents($this->getFile($key));

		if ( !$val ) {
			return false;
		}

		return json_decode($val, true);
	}

	private function getFile($key, $create = false) {

		$key = md5($key);
		$dir = $this->getDir($key, $create);

		return $dir ? $dir .'/'. $key : false;
	}

	private function removeFile($key) {

		return $this->getFile($key) && is_file($this->getFile($key)) && unlink($this->getFile($key));
	}

	private function getDir($key, $create) {

		$dir = $this->config['path'] .'/'. str_split($key, 2)[0];

		if ( $create && !is_dir($dir) && !$this->createDir($dir) ) {
			return false;
		}

		return $dir;
	}

	private function createDir($dir) {

		return mkdir($dir, 0777, true);
	}
}