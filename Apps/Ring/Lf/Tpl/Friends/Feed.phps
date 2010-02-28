<?php
class R_Lf_Tpl_Friends_Feed extends R_Lf_Template {
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