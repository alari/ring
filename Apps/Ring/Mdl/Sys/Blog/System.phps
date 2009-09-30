<?php
/**
 * @table blog
 *
 * @field:config system -inverse blog
 */
class R_Mdl_Blog_System extends R_Mdl_Site_SysInstance {
	const CREATIVE_CLASS = "R_Mdl_Blog_Post";

	/**
	 * Returns creative by its id, if accessible
	 *
	 * @param int $id
	 * @return R_Mdl_Blog_Post
	 */
	public function getCreative( $id )
	{
		return $this->getCreativeById( $id, "R_Mdl_Blog_Post" );
	}

	public function url( $page = 1 )
	{
		return $this->system->url( $page > 1 ? "page-$page" : "" );
	}

	public function addFormTitle() {
		return "Новая запись в блоге, ".$this->title;
	}

	public function editFormTitle() {
		return "Правка записи в блоге, ".$this->title;
	}

}