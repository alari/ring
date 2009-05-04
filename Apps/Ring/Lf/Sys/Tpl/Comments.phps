<?php
class R_Lf_Sys_Tpl_Comments extends R_Lf_Sys_Template {
	public $paginator;
	public $title;

	public function displayContents()
	{
		echo "<h1>Отзывы: ".$this->instance->system->link()."</h1>";

		if ($this->paginator)
			$this->paginator->show( $this->layout(), "list" );
	}

}