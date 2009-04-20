<?php
/**
 * @field system -owns one R_Mdl_Site_System
 * @field title VARCHAR(255) -show linkInContainer
 */
abstract class R_Mdl_Site_SysInstance extends O_Dao_ActiveRecord {

	/**
	 * Returns command by page urlpart
	 *
	 * @param string $page
	 * @return R_Command
	 */
	abstract public function getCommand( $page );

	/**
	 * Returns one creative by its id, if it's in this system instance and can be viewed
	 *
	 * @param int $id
	 * @return R_Mdl_Site_Creative
	 */
	abstract public function getCreative( $id );

	/**
	 * Returns creative by id and class
	 *
	 * @param int $id
	 * @param string $class
	 * @return R_Mdl_Site_Creative
	 */
	protected function getCreativeById( $id, $class )
	{
		$item = O_Dao_ActiveRecord::getById( $id, $class );
		if (!$item instanceof R_Mdl_Site_Creative)
			return false;
		if ($item->anonce->system->instance != $this)
			return false;
		if (!R_Mdl_Session::can( "read " . $item->anonce[ "access" ], $this->system->site ))
			return false;
		return $item;
	}
}