<?php namespace Sc3n3\FatSlim\Services\Database;

use Illuminate\Database\Capsule\Manager;

class DatabaseService {

	public function __construct($instance) {

		$active = $instance->config('database')['active'];
		$drivers = $instance->config('database')['drivers'];

		if ( !$active ) {
			return;
		}

		$manager = new Manager;
		$manager->addConnection($drivers[$active]);

		$manager->setAsGlobal();
		$manager->bootEloquent();

		//\DB::setInstance($manager);

		$instance->db = $manager->getConnection();

		if ( $instance->config('debug') ) {
			$manager->getConnection()->enableQueryLog();
		}
	}
	
}