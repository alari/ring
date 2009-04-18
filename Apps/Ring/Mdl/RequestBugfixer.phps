<?php

class R_Mdl_RequestBugfixer {

	static public function fix()
	{
		$params = O_Registry::get( "app/env/params" );
		if(count($params)) {print_r($params);exit;}
		array_walk_recursive( $params, array (__CLASS__, "callback") );
		O_Registry::set( "app/env/params", $params );
	}

	static public function callback( &$param, $key )
	{
		if (is_string( $param ))
			$param = str_replace( "�?", "ш", $param );
	}

}

O_ClassManager::registerClassLoadedCallback( array ("R_Mdl_RequestBugfixer", "fix"), "O_Command" );