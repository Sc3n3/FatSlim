<?php namespace Sc3n3\FatSlim\Services\Facade\Facades;

use Illuminate\Validation\Factory as ValidatorFactory;
use Symfony\Component\Translation\Translator;
use Symfony\Component\Translation\MessageSelector;
use Sc3n3\FatSlim\Services\Facade\FacadeBase;

class Validator extends FacadeBase { 

	public static function _getProxyItem() {

		return new ValidatorFactory(new Translator('en_US', new MessageSelector()));;
	}
}