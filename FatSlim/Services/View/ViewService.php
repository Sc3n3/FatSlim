<?php namespace Sc3n3\FatSlim\Services\View;

use Sc3n3\FatSlim\Bootstrap;

class ViewService {

	private static $instance = null;

	public function __construct($instance) {

		$viewClass = $instance->config('template.engine');
		if( !$viewClass || !class_exists($viewClass) ) {
			throw new \RuntimeException('Template Engine is not found!');
		}

		$engine = $this->setEngine(new $viewClass);

		$instance->config('templates.path', $instance->path('/app/Views'));

		$instance->config('view', $engine);
		$instance->view->parserOptions = array(
			'debug' => $instance->config('debug'),
			'cache' => $instance->config('cache_dir') .'/view'
		);
	}

	public function setEngine($class) {

		if( !$class instanceof \Slim\View ) {
			throw new \RuntimeException('Template Engine is not valid!');
		}

		return self::$instance = $class;
	}

	public static function addNamespace($prefix, $path) {

		return self::$instance->addNamespace($prefix, $path);
	}
}
