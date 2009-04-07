<?php
class R_Ctr_Tpl_Default extends R_Template {
	protected $layoutClass = "R_Ctr_Lo_Main";

	public function displayContents()
	{
		$this->layout()->addHeadLink( "openid.server", 
				"http://" . O_Registry::get( "app/env/http_host" ) . O_UrlBuilder::get( "openid/server" ) );
		echo "Ctr";
	}

}