<?php namespace Sc3n3\FatSlim\Services\Middleware;

class MiddlewareService {

	public function __construct($instance) {

		$middlewares = (array) $instance->config('middlewares');
		
		foreach( $middlewares as $middleware ) {
			$instance->add(new $middleware);
		}

	}
}