<?php
/**
 * @table sys_libro
 *
 * @field:config system -inverse libro
 */
class R_Mdl_Sys_Libro_System extends R_Mdl_Sys_Implementation {
	const CREATIVE_CLASS = "R_Mdl_Sys_Libro_Text";

	public function addFormTitle()
	{
		return "Новое лит. произведение, " . $this->title;
	}

	public function editFormTitle()
	{
		return "Правка произведения, " . $this->title;
	}

}