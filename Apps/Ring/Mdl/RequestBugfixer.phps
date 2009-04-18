<?php

class R_Mdl_RequestBugfixer {
	static public function fix() {
		$params = O_Registry::get("app/env/params");
		O_Registry::set("app/env/params", array_map(array(__CLASS__, "callback"), $params));
	}

	static public function callback($param) {
		if(is_string($param))return str_replace("�?", "ш", $param);
		if(is_array($param)) return array_map(array(__CLASS__, "callback"), $param);
		return $param;
	}

}

O_ClassManager::registerClassLoadedCallback(array("R_Mdl_RequestBugfixer", "fix"), "O_Command");