<?php
/**
 * @table blog
 *
 * @field:config system -inverse blog
 */
class R_Mdl_Sys_Blog_System extends R_Mdl_Sys_Instance {
	const CREATIVE_CLASS = "R_Mdl_Sys_Blog_Post";

	public function addFormTitle() {
		return "Новая запись в блоге, ".$this->title;
	}

	public function editFormTitle() {
		return "Правка записи в блоге, ".$this->title;
	}

}