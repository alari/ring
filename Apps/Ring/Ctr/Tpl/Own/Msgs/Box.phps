<?php
class R_Ctr_Tpl_Own_Msgs_Box extends R_Ctr_Template {
	public $paginator;
	public $box;

	public function displayContents()
	{
		if ($this->paginator)
			$this->paginator->show( $this->layout() );
	}

}