<?php namespace Sc3n3\FatSlim\Views;

class Slim extends \Slim\View
{

    public $parserOptions = array();
    private $namespaces = array();

    public function render($template, $data = null)
    {

        $module = ( preg_match('/^@([^\/]+)\//', $template, $match) || preg_match('/^([^::]+)::/', $template, $match) );
        
        if( $module && !isset($this->namespaces[$match[1]]) ) {
            throw new \RuntimeException('ViewPath does not exist for \''. rtrim($match[0], '/') .'\'');
        } elseif( $module ) {
            $template = str_replace($match[0], '', $template);
            parent::setTemplatesDirectory($this->namespaces[$match[1]]);
        } else {
            parent::setTemplatesDirectory($this->namespaces['__default__']);
        }

        $template = $template .'.slim.php';

        $data = array_merge($this->all(), (array) $data);
        try {
            $output = parent::render($template, $data);
        } catch (Exception $e) {
			throw new \RuntimeException($e->getMessage());
        }

        return $output;
    }

    public function setTemplatesDirectory($dir, $prefix = '__default__') {

        return $this->addNamespace($prefix, $dir);
    }

    public function addNamespace($prefix, $path) {

        return $this->namespaces[$prefix] = $path;
    }
}
