<?php namespace Sc3n3\FatSlim\Modules\Admin\Controllers;

use Sc3n3\FatSlim\BaseController;
use Sc3n3\FatSlim\Modules\Admin\Models\AdminModel;

class IndexController extends BaseController {
	
	public function getIndex() {

		return render('@admin/index', array('text' => 'Admin Page'));
		
	}
}

