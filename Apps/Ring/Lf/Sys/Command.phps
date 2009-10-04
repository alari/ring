<?php
abstract class R_Lf_Sys_Command extends R_Lf_Command {
	public $instance;

	public function getTemplate( $tpl = null )
	{
		$tpl = parent::getTemplate( $tpl );
		$tpl->instance = $this->instance;
		return $tpl;
	}
}