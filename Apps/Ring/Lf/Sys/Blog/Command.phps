<?php
abstract class R_Lf_Sys_Blog_Command extends R_Lf_Command {
	public $blog;

	public function getTemplate( $tpl = null )
	{
		$tpl = parent::getTemplate( $tpl );
		$tpl->site = $this->getSite();
		$tpl->blog = $this->blog;
		$tpl->can_write = $this->can( "write", $tpl->site );
		return $tpl;
	}
}