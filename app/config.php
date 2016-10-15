<?php

return array(

	'debug' => true,
	'mode' => 'development',
	'cookies.encrypt' => true,

	'database' => array(

		'active' => 'mysql',
		'drivers' => array(

			'mysql' => array(
				'driver'    => 'mysql',
				'host'      => '127.0.0.1',
				'database'  => 'database',
				'username'  => 'root',
				'password'  => '',
				'charset'   => 'utf8',
				'collation' => 'utf8_unicode_ci',
				'prefix'    => '',
			),
		)
	),
	
	'session' => array(

		'active' => 'cookie',
		'drivers' => array(

			'native' => array( // Adviced
				'maxlifetime' => 3600
			),

			'cookie' => array(
				'expires' => '60 minutes',
				'path' => '/',
				'domain' => null,
				'secure' => true,
				'httponly' => false,
				'name' => 'session'
			),

			'redis' => array(
				'maxlifetime' => 3600,
				'prefix' => 'PHPSESSID:',
				'scheme' => 'tcp',
				'host' => '127.0.0.1',
				'port' => '6379',
				'password' => null,
				'database' => 0
			)
		)
	),

	'cache' => array(
		'active' => 'file',
		'prefix' => null,
		'drivers' => array(

			'array' => array(),

			'file' => array(
				'driver' => 'file',
				'path' => path('cache/storage')
			),

			'redis' => array(
				'scheme' => 'tcp',
				'host' => '127.0.0.1',
				'port' => '6379',
				'password' => null,
				'database' => 1
			)
		)
	),

	//'template.engine' => \Sc3n3\FatSlim\Views\Twig::class,
	'template.engine' => \Sc3n3\FatSlim\Views\Blade::class,
	//'template.engine' => \Sc3n3\FatSlim\Views\Slim::class,
	'services' => array(
		\Sc3n3\FatSlim\Services\Facade\FacadeService::class,
		\Sc3n3\FatSlim\Services\Session\SessionService::class,
		\Sc3n3\FatSlim\Services\Database\DatabaseService::class,
		\Sc3n3\FatSlim\Services\Cache\CacheService::class,
		\Sc3n3\FatSlim\Services\View\ViewService::class,
		\Sc3n3\FatSlim\Services\Module\ModuleService::class,
		\Sc3n3\FatSlim\Services\Middleware\MiddlewareService::class,
	),
	'middlewares' => array(
		\Slim\Extras\Middleware\CsrfGuard::class
	),
	'aliases' => array(
		'App' 		=> Sc3n3\FatSlim\Services\Facade\Facades\App::class,
		'Cache' 	=> Sc3n3\FatSlim\Services\Facade\Facades\Cache::class,
		'Config' 	=> Sc3n3\FatSlim\Services\Facade\Facades\Config::class,
		'Container'	=> Sc3n3\FatSlim\Services\Facade\Facades\Container::class,
		'DB' 		=> Sc3n3\FatSlim\Services\Facade\Facades\DB::class,
		'Input' 	=> Sc3n3\FatSlim\Services\Facade\Facades\Input::class,
		'Request' 	=> Sc3n3\FatSlim\Services\Facade\Facades\Request::class,
		'Response' 	=> Sc3n3\FatSlim\Services\Facade\Facades\Response::class,
		'Route' 	=> Sc3n3\FatSlim\Services\Facade\Facades\Route::class,
		'Schema' 	=> Sc3n3\FatSlim\Services\Facade\Facades\Schema::class,
		'Validator'	=> Sc3n3\FatSlim\Services\Facade\Facades\Validator::class,
		'View' 		=> Sc3n3\FatSlim\Services\Facade\Facades\View::class
	),
	'modules' => array(
		\Sc3n3\FatSlim\Modules\Admin\AdminProvider::class
	)
);