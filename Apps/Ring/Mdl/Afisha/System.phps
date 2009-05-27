<?php
/**
 * @table afisha
 *
 * @field:config system -inverse afisha
 */
class R_Mdl_Afisha_System extends R_Mdl_Site_SysInstance {
	const CREATIVE_CLASS = "R_Mdl_Afisha_Bill";

	/**
	 * Returns creative by its id, if accessible
	 *
	 * @param int $id
	 * @return R_Mdl_Blog_Post
	 */
	public function getCreative( $id )
	{
		return $this->getCreativeById( $id, "R_Mdl_Afisha_Bill" );
	}

	public function url( $page = 1 )
	{
		return $this->system->url( $page > 1 ? "page-$page" : "" );
	}

	public function addFormTitle() {
		return "Новое событие, ".$this->title;
	}

	public function editFormTitle() {
		return "Правка события, ".$this->title;
	}

}