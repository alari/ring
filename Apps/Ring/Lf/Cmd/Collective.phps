<?php
class R_Lf_Cmd_Collective extends R_Lf_Command {

	public function process()
	{
		$tpl = $this->getTemplate();

		$tpl->leaders = $this->getSite()->getTypicalGroup(R_Mdl_User_Group::TYPE_ADMIN)->getUsers();
		$tpl->members = $this->getSite()->getTypicalGroup(R_Mdl_User_Group::TYPE_MEMBER)->getUsers();
		$tpl->readers = $this->getSite()->{"relations.user"}->test("flags", R_Mdl_User_Relationship::FLAG_FOLLOW, "&");

		return $tpl;
	}
}