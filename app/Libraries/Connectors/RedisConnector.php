<?php namespace Sc3n3\FatSlim\Connectors;

use Predis\Client;
use Predis\Connection\ConnectionException;

class RedisConnector {

	private $config = array();

	public function __construct( $config ) {

		$defaults = array(
			'scheme' => 'tcp',
			'host' => '127.0.0.1',
			'port' => '6379',
			'password' => null,
			'database' => 0
		);

		$this->config = array_merge($defaults, $config);
	}

	public function connect() {

		try {

			$client = new Client($this->config);
			return $client->connect();

		} catch (ConnectionException $e) {

			return false;
		}
	}
}
