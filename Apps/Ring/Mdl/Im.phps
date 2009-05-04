<?php
/**
 * @table im_system
 *
 * @field:config system -inverse im
 *
 */
class R_Mdl_Im extends R_Mdl_Site_SysInstance {
	const CREATIVE_CLASS = "R_Mdl_Im_Picture";


	/**
	 * Returns creative by its id, if accessible
	 *
	 * @param int $id
	 * @return R_Mdl_Im_Picture
	 */
	public function getCreative( $id )
	{
		return $this->getCreativeById( $id, "R_Mdl_Im_Picture" );
	}

	public function addFormTitle() {
		return "Новая работа, ".$this->title;
	}

	public function editFormTitle() {
		return "Настройки картинки, ".$this->title;
	}
}