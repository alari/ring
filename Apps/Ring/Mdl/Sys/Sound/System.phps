<?php
/**
 * @table sound
 *
 * @field:config system -inverse sound
 */
class R_Mdl_Sound_System extends R_Mdl_Site_SysInstance {
	const CREATIVE_CLASS = "R_Mdl_Sound_Track";

	/**
	 * Returns creative by its id, if accessible
	 *
	 * @param int $id
	 * @return R_Mdl_Sound_Track
	 */
	public function getCreative( $id )
	{
		return $this->getCreativeById( $id, "R_Mdl_Sound_Track" );
	}

	public function url( $page = 1 )
	{
		return $this->system->url( $page > 1 ? "page-$page" : "" );
	}

	public function addFormTitle() {
		return "Новая композиция, ".$this->title;
	}

	public function editFormTitle() {
		return "Настройки композиции, ".$this->title;
	}

}