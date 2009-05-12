<?php
class R_Lf_Tpl_Comments extends R_Lf_Template {
	public $paginator;

	public function displayContents()
	{
		echo "<h1>Комментарии на сайте: ".$this->site->link()."</h1>";

		$this->layout()->setTitle("Комментарии на сайте");

		if ($this->paginator)
			$this->paginator->show( $this->layout(), "list" );
	}

}