<?php namespace Sc3n3\FatSlim\Services\Cache;

use Sc3n3\FatSlim\Services\Cache\Drivers\CacheDriverInterface;

class CacheService {

    private $prefix = '';
    private $instance = null;

    public function __construct($instance) {

        $active = $instance->config('cache')['active'];
        $drivers = $instance->config('cache')['drivers'];

        $this->setPrefix($instance->config('cache')['prefix']);

        try {

            switch ($active) {
                case 'file':
                    $this->setDriver(new Drivers\FileDriver($drivers['file']));
                    break;

                case 'redis':
                    $this->setDriver(new Drivers\RedisDriver($drivers['redis']));
                    break;

                default:
                    throw new \Exception('Default Cache');
                    break;
            }

        } catch(\Exception $e) {
            $this->setDriver(new Drivers\ArrayDriver);
        }

        $instance->cache = $this;
    }

    public function get($key) {

        return $this->unserialize($this->instance->get($this->key($key)));
    }

    public function pull($key) {

        return $this->get($key);
    }

    public function set($key, $val, $expire = 60) {

        $val = $this->call($val);
        $action = $this->instance->set($this->key($key), $this->serialize($val), $expire);

        return $action ? $val : false;
    }

    public function put($key, $val, $expire = 60) {

        return $this->set($key, $val, $expire);
    }

    public function add($key, $val, $expire = 60) {

        if( $this->has($key) ) {
            return false;
        }

        return $this->set($key, $val, $expire);
    }

    public function forever($key, $val) {

        return $this->set($key, $val, 0);
    }

    public function has($key) {

    	return $this->instance->has($this->key($key));
    }

    public function del($key) {

    	return $this->instance->del($this->key($key));
    }

    public function forget($key) {

        return $this->del($key);
    }

    public function flush() {

        return $this->instance->flush();
    }

    public function remember($key, $val, $expire = 60) {
    	
        if ( $value = $this->get($this->key($key)) ) {
            return $value;
        }

        return $this->set($key, $val, $expire);
    }

    public function setDriver(CacheDriverInterface $instance) {
    	
    	return $this->instance = $instance;
    }

    public function setPrefix($prefix) {

        return $this->prefix = $prefix;
    }

    // *********************************

    private function key($key) {

        return !empty($this->prefix) ? $this->prefix .':'. $key : $key;
    }

    private function call($value) {

        return is_callable($value) ? call_user_func($value) : $value;
    }

    private function serialize($value) {

        return is_object($value) || is_array($value) ? serialize($value) : $value;
    }
    
    private function unserialize($value) {

        return !($val = @unserialize($value)) ? $value : $val;
    }
}
