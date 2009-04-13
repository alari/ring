<?php
class R_Lf_Sys_Blog_Tpl_Home extends R_Lf_Sys_Blog_Template {
	public $paginator;
	public $title;

	public function displayContents()
	{
		if ($this->title) {
			echo "<h1>", $this->title, "</h1>";
			$this->layout()->setTitle( $this->title );
		}
		if ($this->paginator)
			$this->paginator->show( $this->layout() );
	}

}