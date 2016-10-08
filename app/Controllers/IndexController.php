<?php namespace App\Controllers;

use Cache;
use App\Models\UserModel;

class IndexController {
	
	public static function getIndex() {
		
		return render('index.twig', array('name' => 'World'));
	}

	public static function getTest() {
		
		dd( UserModel::all()->toArray() );
	}
}

