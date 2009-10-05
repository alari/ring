<?php
class R_Ctr_Tpl_Own_Msgs_Read extends R_Ctr_Template {
	
	public $msg;

	public function displayContents()
	{
		if ($this->msg) {
			$this->msg->show( $this->layout() );
		}
	}
}