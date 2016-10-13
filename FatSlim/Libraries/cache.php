<?php 

use Sc3n3\FatSlim\Services\Cache\CacheService;

class Cache {

    private static $instance = null;

    public static function __callStatic($name, $arguments) {

        return call_user_func_array(array(self::$instance, $name), $arguments);
    }

    public static function setInstance(CacheService $instance) {
    	
    	return self::$instance = $instance;
    }

}
