<?php
class R_Lf_Sys_Tpl_Comments extends R_Lf_Sys_Template {
	public $paginator;
	public $title;

	public function displayContents()
	{
		echo "<h1>Комментарии: ".$this->instance->system->link()."</h1>";

		$this->layout()->setTitle("Комментарии - ".$this->instance->title);

		if ($this->paginator)
			$this->paginator->show( $this->layout(), "list" );
	}

}