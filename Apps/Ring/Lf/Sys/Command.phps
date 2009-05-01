<?php
abstract class R_Lf_Sys_Command extends R_Lf_Command {
	public $instance;

	public function getTemplate( $tpl = null )
	{
		$tpl = parent::getTemplate( $tpl );
		$tpl->instance = $this->instance;
		$tpl->can_write = $this->can( "write", $this->getSite() );
		$tpl->can_delete = $this->can( "delete", $this->getSite() );
		return $tpl;
	}
}