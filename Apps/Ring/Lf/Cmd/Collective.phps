<?php
class R_Lf_Cmd_Collective extends R_Lf_Command {

	public function process()
	{
		$tpl = $this->getTemplate();

		$tpl->leaders = $this->getSite()->usr_related->test("flags", R_Mdl_User_Relation::FLAG_IS_LEADER, "&");
		$tpl->admins = $this->getSite()->usr_related->test("flags", R_Mdl_User_Relation::FLAG_IS_ADMIN, "&");
		$tpl->members = $this->getSite()->members;
		$tpl->readers = $this->getSite()->{"usr_related.user"}->test("flags", R_Mdl_User_Relation::FLAG_WATCH, "&")->where("flags & ? = 0", R_Mdl_User_Relation::FLAGS_COMM);

		return $tpl;
	}

	public function isAuthenticated()
	{
		$site =$this->getSite();
		return $site["type"] == R_Mdl_Site::TYPE_COMM;
	}
}