<?php

class Route {

	public static function set($closure) {

		if( !is_callable($closure) ) {
			return false;
		}

		return call_user_func($closure, app());
	}
}