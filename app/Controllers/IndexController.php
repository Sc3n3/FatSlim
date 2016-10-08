<?php namespace App\Controllers;

use Cache;
use App\Models\UserModel;

class IndexController {
	
	public static function getIndex() {

		return render('index.twig', array('name' => 'World'));

	}

	public static function getModelTest() {
		
		dd( UserModel::all()->toArray() );

	}

	public static function getCacheTest() {

		Cache::set('test', 'Yupp Cached', 60);
		dd( Cache::get('test') );
		
	}
}

