<?php namespace App\Controllers;

use App\Models\UserModel;

class IndexController {
	
	public static function getIndex() {

		return render('index.html', array('name' => 'World'));
	}

	public static function getTest() {
		
		dd( UserModel::all()->toArray() );
	}
}