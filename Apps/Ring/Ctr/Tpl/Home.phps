<?php
class R_Ctr_Tpl_Default extends R_Ctr_Template {
	public function displayContents()
	{
		$this->layout()->addHeadLink( "openid.server",
				"http://" . O_Registry::get( "app/env/http_host" ) . O_UrlBuilder::get( "openid/server" ) );

		?><h1>Центр кольца</h1><?

		O_Dao_Query::get("R_Mdl_Site")->orderBy("RAND()")->show($this->layout());
	}
}