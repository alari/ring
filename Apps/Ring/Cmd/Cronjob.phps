<?php
class R_Cmd_Cronjob extends R_Command {

	public function process()
	{
		echo "<h1>Cronjob</h1>";
		O_Mail_Service::handleQueue();
		echo "<h4>".round( microtime( true ) - O_Registry::get( "start-time" ), 4 )."</h4>";
	}
}