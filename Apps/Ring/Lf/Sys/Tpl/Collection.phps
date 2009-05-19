<?php
class R_Lf_Sys_Tpl_Collection extends R_Lf_Sys_Template {
	public function displayContents()
	{
		$this->collection->show( $this->layout() );

		$this->layout()->setTitle( $this->collection->title . " - " . $this->instance->title );
	}
}