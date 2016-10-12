<?php namespace App\Controllers;

use Cache;
use Validator;
use App\Models\UserModel;
use Sc3n3\FatSlim\BaseController;

class IndexController extends BaseController {
	
	public function getIndex() {

		return render('index', array('name' => 'World'));
		
	}

	public function getModelTest() {

		predump( \DB::table('users')->where('id', '1')->get()->toArray() );
		predd( UserModel::all()->toArray() );

	}

	public function getCacheTest() {
		
		Cache::set('test', 'Yupp Cached', 60);
		dd( Cache::get('test') );
		
	}

	public function getValidatorTest() {

		$values = array();
		$errors = array('No Error');

		$rules = array(
		    'username' => ['required', 'min:3', 'max:20'],
		    'password' => ['required', 'min:5', 'max:60']
		);

		$messages = array(
		    'username.required' => 'Username is required.',
		    'username.min' => 'Username must be at least :min characters.',
		    'username.max' => 'Username must be no more than :max characters.',
		    'password.required' => 'Password is required.',
		    'password.min' => 'Password must be at least :min characters.',
		    'password.max' => 'Password must be no more than :max characters.',
		);

		$validator = Validator::make($values, $rules, $messages);

		if ($validator->fails()) {
		    $errors = $validator->messages();
		}

		dd( $errors );
	}
}

