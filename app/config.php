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
		'active' => 'array',
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
	'modules' => array(
		\Sc3n3\FatSlim\Modules\Admin\AdminProvider::class
	)
);