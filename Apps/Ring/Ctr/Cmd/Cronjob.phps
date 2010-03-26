<?php
class R_Ctr_Cmd_Cronjob extends R_Command {

	public function process()
	{
		echo "<h1>Cronjob</h1>";
		O_Mail_Service::handleQueue();
		R_Mdl_Site_Crosspost::handleQueue();
		R_Mdl_User_EmailConfirm::gc();
		echo "<h4>" . round( microtime( true ) - O( "*start-time" )) . "</h4>";
	}
}