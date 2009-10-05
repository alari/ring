<?php
/**
 * @table sys_forum
 *
 * @field:config system -inverse forum
 */
class R_Mdl_Sys_Forum_System extends R_Mdl_Sys_Implementation {
	const CREATIVE_CLASS = "R_Mdl_Sys_Forum_Thread";

	public function addFormTitle()
	{
		return "Новое событие, " . $this->title;
	}

	public function editFormTitle()
	{
		return "Правка события, " . $this->title;
	}

}