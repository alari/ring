<?php
class R_Lf_Tpl_Tag extends R_Lf_Template {
	public $tag;
	public $tags;
	public $pager;

	public function displayContents()
	{
		echo "<h1>", $this->tag->title, "</h1>";
		
		$this->layout()->setTitle( $this->tag->title . " - Метка" );
		
		$this->pager->show( $this->layout(), "full" );
	}

	public function displayNav()
	{
		R_Fr_Site_Tag::showCloud( $this->tags );
	}

}