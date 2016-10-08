<?php

return array(

	'debug' => true,
	'mode' => 'development',

	'database' => array(

		'active' => 'mysql',
		'drivers' => array(

			'mysql' => array(
				'driver'    => 'mysql',
			    'host'      => 'localhost',
			    'database'  => 'database',
			    'username'  => 'root',
			    'password'  => '',
			    'charset'   => 'utf8',
			    'collation' => 'utf8_unicode_ci',
			    'prefix'    => '',
			)
		)
	),
	
	'session' => array(

		'active' => 'native',
		'drivers' => array(

			'native' => array(), // Adviced

			'cookie' => array(
				'expires' => '60 minutes',
				'path' => '/',
				'domain' => null,
				'secure' => true,
				'httponly' => false,
				'name' => 'session'
			),

			'redis' => array() // For future
		)
	)
);