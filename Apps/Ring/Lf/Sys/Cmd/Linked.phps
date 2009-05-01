<?php
class R_Lf_Sys_Cmd_Linked extends R_Lf_Sys_Command {
	public $creative_id;

	public function process()
	{
		$creative = $this->instance->getCreative( $this->creative_id );
		if (!$creative)
			return $this->redirect( "/" );

		if($this->isMethodPost() && $this->getParam("link_target")) {
			$target = R_Mdl_Site_Anonce::getById($this->getParam("link_target"), "R_Mdl_Site_Anonce");
			if($target && $target->isVisible()) {
				$creative->anonce->linked[] = $target;
				return $this->redirect();
			}
		}

		if($this->getParam("remove")) {
			$target = R_Mdl_Site_Anonce::getById($this->getParam("remove"), "R_Mdl_Site_Anonce");
			if($target) {
				$creative->anonce->linked->remove($target);
			}
		}

		$tpl = $this->getTemplate();
		$tpl->linked = $creative->anonce->linked;
		$tpl->creative = $creative;
		return $tpl;
	}

	public function isAuthenticated()
	{
		return $this->instance && $this->can( "read " . $this->instance->system[ "access" ], $this->getSite() ) && $this->can(
				"write", $this->getSite() );
	}

}