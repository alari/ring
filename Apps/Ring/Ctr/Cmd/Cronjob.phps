<?php
class R_Ctr_Cmd_Cronjob extends R_Command {

	public function process()
	{
		echo "<h1>Cronjob</h1>";
		O_Mail_Service::handleQueue();
		R_Mdl_Site_Crosspost::handleQueue();
		echo "<h4>" . round( microtime( true ) - O_Registry::get( "start-time" ), 4 ) . "</h4>";

		$ims = O_Dao_Query::get("R_Mdl_Sys_Im_Picture");
		foreach($ims as $im) {
			$path = $im->imgPath("full");
			if(!is_file($path)) {
				echo "<h4>".$path." // #$im->id</h4>";
				continue;
			}
			$r = new O_Image_Resizer($path);
			$r->resize(150,150,"./imr.tmp");
			$im->img_tiny = $r;
			unset($r);
		}
	}
}