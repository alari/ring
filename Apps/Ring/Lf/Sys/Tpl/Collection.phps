<?php
class R_Lf_Sys_Tpl_Collection extends R_Lf_Sys_Template {
	public function displayContents()
	{
		$this->collection->show( $this->layout() );

		$this->layout()->setTitle( $this->collection->title . " - " . $this->instance->title );
	}

	public function displayNav() {
		if(R_Mdl_Session::can("write ".$this->instance->system["access"], $this->getSite())){
		?><p><i><a href="<?=$this->collection->url()?>/form">Редактировать коллекцию</a></i></p><?
		}
		parent::displayNav();
	}

}