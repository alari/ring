<?php
class R_Lf_Sys_Cmd_Home extends R_Lf_Sys_Command {
	public $instance;
	public $tag;

	public function process()
	{
		$tpl = $this->getTemplate();
		$tpl->tags = $this->instance->system->{"anonces.tags"};
		return $tpl;
	}

	public function isAuthenticated()
	{
		return $this->instance && $this->can( "read " . $this->instance->system[ "access" ], $this->getSite() );
	}

}