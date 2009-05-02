<?php
class R_Lf_Sys_Tpl_Creative extends R_Lf_Sys_Template {
	public function displayContents()
	{
		$this->creative->show( $this->layout() );
		$this->creative->nodes->show( $this->layout() );
		if(count($this->creative->anonce->linked)) {
			echo "<br clear=\"right\"/>";
			echo "<br/>";
			$this->creative->anonce->linked->show($this->layout());
		}
		R_Fr_Site_Comment::addForm( $this->creative->anonce->id, $this->creative->system->id );
		$this->layout()->setTitle( $this->creative->anonce->title . " - " . $this->creative->system->instance->title );
	}

}