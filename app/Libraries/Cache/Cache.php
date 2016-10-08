<?php 

use Sc3n3\FatSlim\Cache\CacheInterface;

class Cache {

    private static $prefix = '';
    private static $instance = null;

    public static function get($key) {

        return self::unserialize(self::$instance->get(self::key($key)));
    }

    public static function set($key, $val, $expire = 0) {

        $val = self::call($val);
        $action = self::$instance->set(self::key($key), self::serialize($val), $expire);

        return $action ? $val : false;
    }

    public static function has($key) {

    	return self::$instance->has(self::key($key));
    }

    public static function del($key) {

    	return self::$instance->del(self::key($key));
    }

    public static function remember($key, $val, $expire = 0) {
    	
        if ( $value = self::get(self::key($key)) ) {
            return $value;
        }

        return self::set($key, $val, $expire);
    }

    public static function setInstance(CacheInterface $instance) {
    	
    	return self::$instance = $instance;
    }

    private static function key($key) {

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