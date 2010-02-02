<?php
class R_Ctr_Cmd_Admin_Init extends R_Command {

	public function process()
	{
		foreach(R_Mdl_Site::getQuery() as $site) {
			echo "<div>Site ".$site->link()."<br/>";
			$leader = $site->owner;
			if(!$leader) {
				$leader = $site->leader->getOne();
			}
			echo "Leader: ".$leader->link()."<br/>";
			R_Mdl_User_Group::createSiteGroups($site, $leader);

			foreach($site->{"usr_related.user"}->test("flags", R_Mdl_User_Relation::FLAG_WATCH, "&")->where("flags & ? = 0", R_Mdl_User_Relation::FLAGS_COMM) as $reader) {
				echo "Adding: ".$reader->link()."<br/>";
				$reader->addFriend($site);
			}
			echo "</div><hr/>";
		}
		foreach(R_Mdl_User::getQuery() as $user) {
			foreach($user->friends as $f) {
				$user->addFriend($f);
			}
		}
	}
}