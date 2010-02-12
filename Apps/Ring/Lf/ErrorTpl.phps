<?php
class R_Lf_ErrorTpl extends R_Lf_Template {
	/**
	 *
	 * @var Exception
	 */
	public $ex;

	public function displayContents()
	{
		echo "<h1>Ошибка</h1>";
		echo "<h4>", $this->ex->getMessage(), "</h4>";

		$this->layout()->setTitle( "Ошибка #".$this->ex->getCode() );

	}

}