<?php
class R_Mr_Tpl_Topic extends R_Template {
	public $topic;

	public function displayContents()
	{
		$this->layout()->setTitle( "Рубрика: " . $this->topic->title );
		
		$this->topic->show( $this->layout() );
	}

}