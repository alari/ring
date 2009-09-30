<?php
/**
 * @table libro
 *
 * @field:config system -inverse libro
 */
class R_Mdl_Libro_System extends R_Mdl_Site_SysInstance {
	const CREATIVE_CLASS = "R_Mdl_Libro_Text";

	/**
	 * Returns creative by its id, if accessible
	 *
	 * @param int $id
	 * @return R_Mdl_Blog_Post
	 */
	public function getCreative( $id )
	{
		return $this->getCreativeById( $id, "R_Mdl_Libro_Text" );
	}

	public function url( $page = 1 )
	{
		return $this->system->url( $page > 1 ? "page-$page" : "" );
	}

	public function addFormTitle() {
		return "Новое лит. произведение, ".$this->title;
	}

	public function editFormTitle() {
		return "Правка произведения, ".$this->title;
	}

}