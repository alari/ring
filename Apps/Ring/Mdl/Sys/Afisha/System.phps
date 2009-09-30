<?php
/**
 * @table afisha
 *
 * @field:config system -inverse afisha
 */
class R_Mdl_Sys_Afisha_System extends R_Mdl_Site_SysInstance {
	const CREATIVE_CLASS = "R_Mdl_Sys_Afisha_Bill";

	public function addFormTitle() {
		return "Новое событие, ".$this->title;
	}

	public function editFormTitle() {
		return "Правка события, ".$this->title;
	}

}