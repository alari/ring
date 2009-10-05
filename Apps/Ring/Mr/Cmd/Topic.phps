<?php
class R_Mr_Cmd_Topic extends R_Command {

	public function process()
	{
		$topic = O_Registry::get( "app/current/topic" );
		
		if (!$topic) {
			$this->setNotice( "Рубрика не найдена." );
			return $this->redirect( "" );
		}
		
		$tpl = $this->getTemplate();
		$tpl->topic = $topic;
		return $tpl;
	}

}