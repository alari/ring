<?php
class R_Lf_Sys_Tpl_Home extends R_Lf_Sys_Template {
	public function displayContents()
	{
		?>
<h1><?=$this->instance->system->title?></h1>
		<?
		$this->layout()->setTitle( $this->instance->system->title );
		$this->layout()->addHeadLink("alternate", $this->instance->system->url("rss"), "application/rss+xml", "RSS");

		$this->instance->system->show($this->layout(), "own");
	}

}