<?php
class R_Ctr_Cmd_Cronjob extends R_Command {

	public function process()
	{
		echo "<h1>Cronjob</h1>";
		O_Mail_Service::handleQueue();
		R_Mdl_Site_Crosspost::handleQueue();
		echo "<h4>".round( microtime( true ) - O_Registry::get( "start-time" ), 4 )."</h4>";

		if($this->getParam("restore_friends")){
			O_Dao_TableInfo::get("R_Mdl_User_Relation")->createTable();
			$q = O_Db_Query::get("user_relation_backup")->select();
			foreach($q as $a) {
				$user = O_Dao_Query::get("R_Mdl_User")->test("id", $a["user"])->getOne();
				if($a["author"]) {
					$target = O_Dao_Query::get("R_Mdl_User")->test("id", $a["author"])->getOne();
				} else {
					$target = O_Dao_Query::get("R_Mdl_Site")->test("id", $a["site"])->getOne();
				}
				R_Mdl_User_Relation::addFriend($user, $target);
			}
		}
	}
}