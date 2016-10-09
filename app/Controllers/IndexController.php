<?php namespace App\Controllers;

use Cache;
use App\Models\UserModel;

class IndexController extends BaseController {
	
	public function getIndex() {

		return render('index.twig', array('name' => 'World'));
	}

	public function getModelTest() {
		
		dd( UserModel::all()->toArray() );

	}

	public function getCacheTest() {
		
		Cache::set('test', 'Yupp Cached', 60);
		dd( Cache::get('test') );
		
	}
}

