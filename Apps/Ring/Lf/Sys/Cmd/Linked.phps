<?php
class R_Lf_Sys_Cmd_Linked extends R_Lf_Sys_Command {
	public $creative_id;
	protected $creative;

	public function process()
	{
		if($this->isMethodPost() && $this->getParam("link_target")) {
			$target = R_Mdl_Site_Anonce::getById($this->getParam("link_target"), "R_Mdl_Site_Anonce");
			if($target && $target->isVisible()) {
				$this->creative->anonce->linked[] = $target;
				return $this->redirect();
			}
		}

		if($this->getParam("remove")) {
			$target = R_Mdl_Site_Anonce::getById($this->getParam("remove"), "R_Mdl_Site_Anonce");
			if($target) {
				$this->creative->anonce->linked->remove($target);
			}
		}

		$tpl = $this->getTemplate();
		$tpl->linked = $this->creative->anonce->linked;
		$tpl->creative = $this->creative;
		return $tpl;
	}

	public function isAuthenticated()
	{
		$this->creative = $this->instance->getCreative( $this->creative_id );
		if (!$this->creative)
			throw new O_Ex_Redirect("/");

		return $this->instance && $this->can( "read " . $this->instance->system[ "access" ], $this->creative->anonce ) && $this->can(
				"write " . $this->instance->system[ "access" ], $this->creative->anonce );
	}

}