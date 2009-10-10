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
			if(!is_file($im->imgPath("full", $im["img_full"]))) {
				echo "<h4>".$im->imgPath("full", $im["img_full"])." // #$im->id</h4>";
				continue;
			}
			$r = new O_Image_Resizer($im->imgPath("full", $im["img_full"]));
			$r->resize(150,150,tempnam(".","imr"));
			$im->img_tiny = $r;
			unset($r);
		}
	}
}