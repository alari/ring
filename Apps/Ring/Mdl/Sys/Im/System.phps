<?php
/**
 * @table im_system
 *
 * @field:config system -inverse im
 *
 */
class R_Mdl_Sys_Im_System extends R_Mdl_Sys_Implementation {
	const CREATIVE_CLASS = "R_Mdl_Sys_Im_Picture";

	public function addFormTitle() {
		return "Новая работа, ".$this->title;
	}

	public function editFormTitle() {
		return "Настройки картинки, ".$this->title;
	}
}