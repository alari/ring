<?php
class R_Lf_Cmd_Collective extends R_Lf_Command {

	public function process()
	{
		$tpl = $this->getTemplate();

		$tpl->groups = $this->getSite()->groups->orderBy("flag");
		$tpl->readers = $this->getSite()->{"relations.user"}->test("flags", R_Mdl_User_Relationship::FLAG_FOLLOW, "&");

		return $tpl;
	}
}