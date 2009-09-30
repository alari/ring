<?php
/**
 * @table sound
 *
 * @field:config system -inverse sound
 */
class R_Mdl_Sys_Sound_System extends R_Mdl_Sys_Implementation {
	const CREATIVE_CLASS = "R_Mdl_Sys_Sound_Track";

	public function addFormTitle() {
		return "Новая композиция, ".$this->title;
	}

	public function editFormTitle() {
		return "Настройки композиции, ".$this->title;
	}

}