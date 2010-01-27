<?php
class R_Ctr_Cmd_Admin_Init extends R_Command {

	public function process()
	{
		R_Mdl_User_Group::getTableInfo()->createTable();
		foreach(R_Mdl_Site::getQuery() as $site) {
			$leader = $site->owner;
			if(!$leader) {
				$leader = $site->leader->getOne();
			}
			R_Mdl_User_Group::createSiteGroups($site, $leader);
			foreach($site->{"usr_related.user"}->test("flags", R_Mdl_User_Relation::FLAG_WATCH, "&")->where("flags & ? = 0", R_Mdl_User_Relation::FLAGS_COMM) as $reader) {
				$reader->addFriend($site);
			}
		}
		foreach(R_Mdl_User::getQuery() as $user) {
			foreach($user->friends as $f) {
				$user->addFriend($f);
			}
		}
	}
}