<?php
class R_Ctr_Tpl_Own_Friends extends R_Ctr_Template {
	/**
	 * Anonces paginator
	 *
	 * @var O_Dao_Paginator
	 */
	public $paginator;
	public $title;

	public function displayContents()
	{
		if ($this->title) {
			echo "<h1>", $this->title, "</h1>";
			$this->layout()->setTitle( $this->title );
		}
		if ($this->paginator) {
			$this->paginator->show( $this->layout(), "full" );

		}
	}

}