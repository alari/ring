<?php
/**
 * @table afisha
 *
 * @field:config system -inverse afisha
 */
class R_Mdl_Sys_Afisha_System extends R_Mdl_Sys_Implementation {
	const CREATIVE_CLASS = "R_Mdl_Sys_Afisha_Bill";

	public function addFormTitle() {
		return "Новое событие, ".$this->title;
	}

	public function editFormTitle() {
		return "Правка события, ".$this->title;
	}

}