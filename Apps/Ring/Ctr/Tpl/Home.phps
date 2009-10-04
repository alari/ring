<?php
class R_Ctr_Tpl_Default extends R_Ctr_Template {

	public function displayContents()
	{
		$this->layout()->addHeadLink( "openid.server",
				"http://" . O_Registry::get( "app/env/http_host" ) . O_UrlBuilder::get(
						"openid/provider" ) );

		?><h1>Взгляд из центра <a
	href="http://<?=O_Registry::get( "app/hosts/project" )?>/">кольца</a></h1><?

		O_Dao_Query::get( "R_Mdl_Site" )->test( "status", R_Mdl_Site::ST_MODERATED )->orderBy(
				"RAND()" )->show( $this->layout() );
	}
}