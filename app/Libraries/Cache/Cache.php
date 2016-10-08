<?php 

use Sc3n3\FatSlim\Cache\CacheInterface;

class Cache {

    private static $prefix = '';
    private static $instance = null;

    public static function get($key) {

        return self::unserialize(self::$instance->get(self::getKey($key)));
    }

    public static function set($key, $val, $expire = 0) {

    	return self::$instance->set(self::getKey($key), self::serialize(self::call($val)), $expire);
    }

    public static function has($key) {

    	return self::$instance->has(self::getKey($key));
    }

    public static function del($key) {

    	return self::$instance->del(self::getKey($key));
    }

    public static function remember($key, $val, $expire = 0) {
    	
        $key = self::getKey($key);

        if ( $value = self::get($key) ) {
            return $value;
        }

        return self::set($key, $val, $expire);
        
    }

    public static function setInstance(CacheInterface $instance) {
    	
    	return self::$instance = $instance;
    }

    private static function getKey($key) {

        return !empty(self::$prefix) ? self::$prefix .':'. $key : $key;
    }

    private static function call($value) {

        return is_callable($value) ? call_user_func($value) : $value;
    }

    private static function serialize($value) {

        return is_object($value) || is_array($value) ? serialize($value) : $value;
    }
    
    private static function unserialize($value) {

        return !($val = @unserialize($value)) ? $value : $val;
    }
}

class RedisCache {

    public $expire = 10;

    public function set($key, $val) {
        $redis = \Caller::getInstance()->redis;

        $key = $this->key($key);
        $data = (is_callable($val) ? $val() : $val);

        $redis->set($key, gzcompress((is_array($data) ? json_encode($data) : $data), 3));
        $redis->expire($key, $this->expire);

        return $data;
    }

    public function get($key) {
        $redis = \Caller::getInstance()->redis;

        $key = $this->key($key);
        $data = $redis->get($key);

        if (is_null($data)) {
            return false;
        }

        $data = gzuncompress($data);
        $decode = json_decode($data, true);

        return (!is_null($decode) ? $decode : $data);
    }

    public function del($key) {
        $redis = \Caller::getInstance()->redis;
        $redis->del($this->key($key));

        return true;
    }

    private function key($key) {
        return hash("crc32b", md5($key));
    }

}

class Cachee {

    public static $cache = USE_CACHE;
    public static $update = false;

    public static function Run($name, $data, $expire = '10', $onlyuser = false) {
        self::$cache = (!isset(\Caller::$siteSettings['use_cache']) ? USE_CACHE : \Caller::$siteSettings['use_cache']);
        if (!self::$cache) {
            return (is_callable($data) ? $data() : $data);
        }

        if ($onlyuser) {
            \Caller::SessionStart();
            $name = $name . '_' . session_id();
        }

        if (REDIS_CACHE) {
            return self::redisCache($name, $data, $expire);
        } else {
            return self::fileCache($name, $data, $expire);
        }
    }


    // #################################################

    private static function redisCache($name, $data, $expire) {

        $redis = new \RedisCache();
        $redis->expire = $expire;

        if (self::$update || !($cached = $redis->get($name))) {
            return $redis->set($name, $data);
        } else {
            return $cached;
        }
    }
}