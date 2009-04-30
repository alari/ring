<?php
/**
 * @table blog
 *
 * @field:config system -inverse blog
 */
class R_Mdl_Blog extends R_Mdl_Site_SysInstance {
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

	private function setAccesses( O_Dao_Query $q )
	{
		$accesses = array ();
		foreach (array_keys( R_Mdl_Site_System::getAccesses() ) as $level) {
			if (R_Mdl_Session::can( "read " . $level, $this->site ))
				$accesses[] = $level;
		}
		return $q->test( "access", $accesses );
	}

}