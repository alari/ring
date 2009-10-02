<?php
class R_Lf_Sys_Cmd_Crosspost extends R_Lf_Sys_Command {

	public function process()
	{
		$tpl = $this->getTemplate();
		$tpl->creative = $this->creative;
		$tpl->tags = $this->creative->tags;
		$tpl->crossposts = $this->creative->anonce->crossposts;
		$ids = clone $tpl->crossposts;
		$ids->field("service");
		$tpl->available_services = $this->getSite()->crosspost_services->test("id", $ids, O_Db_Query::NOT_IN);
		return $tpl;
	}

	public function isAuthenticated()
	{
		if (!$this->instance)
			throw new O_Ex_PageNotFound( "instance not found", 404 );
		$this->creative = $this->instance->getCreative( $this->creative_id );
		if (!$this->creative)
			throw new O_Ex_PageNotFound( "creative not found", 404 );
		return $this->instance && $this->can( "read " . $this->instance->system[ "access" ],
				$this->getSite() ) && $this->can( "write", $this->getSite() );
	}

}