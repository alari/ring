<?php
class R_Lf_Sys_Tpl_Crosspost extends R_Lf_Sys_Template {
	public function displayContents()
	{
		$this->creative->show( $this->layout() );
	}

}