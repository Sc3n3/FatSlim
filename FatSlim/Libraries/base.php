<?php namespace App\Controllers;

class BaseController {

	protected $app = null;
	protected $view = null;
	protected $request = null;
	protected $response = null;

	public function __construct() {

		$this->app = app();
		$this->view = view();
		$this->request = request();
		$this->response = response();
	}

}