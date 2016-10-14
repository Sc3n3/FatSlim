<?php namespace Sc3n3\FatSlim\Services\Facade\Facades;

use Sc3n3\FatSlim\Services\Facade\FacadeBase;

class Route extends FacadeBase
{

    public static function _getProxyItem() {

        return parent::$instance;
    }

    public static function __callStatic($name, $arguments) {

        $methods = array('map', 'get', 'post', 'put', 'patch', 'delete', 'options', 'group', 'any', 'urlFor');

        if( !in_array($name, $methods) ) {
            throw new \RuntimeException($name .' method is not exist!');
        }

        return call_user_func_array(array(parent::$instance, $name), $arguments);
    }   
}
