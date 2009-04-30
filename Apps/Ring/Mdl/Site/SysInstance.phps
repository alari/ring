<?php
/**
 * @field system -owns one R_Mdl_Site_System
 * @field title VARCHAR(255) -show linkInContainer
 * @field perpage TINYINT NOT NULL DEFAULT 15 -title Количество записей на страницу -edit -required Какое-то количество должно быть обязательно
 */
abstract class R_Mdl_Site_SysInstance extends O_Dao_ActiveRecord {

	/**
	 * Returns command instance to handle the request
	 *
	 * @param string $page
	 * @return R_Command
	 */
	public function getCommand( $page )
	{
		$prefix = "R_Lf_Sys_Cmd_";
		$matches = Array ();
		if ($page == "SystemAdmin") {
			$class = $prefix . "SystemAdmin";
			$cmd = new $class( );
		} elseif (preg_match( "#^form(/([0-9]*))?$#", $page, $matches )) {
			$class = $prefix . "Form";
			$cmd = new $class( );
			if (isset( $matches[ 2 ] ))
				$cmd->creative_id = $matches[ 2 ];
		} elseif (preg_match( "#^([0-9]+)$#", $page, $matches )) {
			$class = $prefix . "Creative";
			$cmd = new $class( );
			$cmd->creative_id = $matches[ 1 ];
		} elseif (preg_match( "#^page-([0-9]+)$#", $page, $matches ) || $page == "Home") {
			$class = $prefix . "Home";
			$cmd = new $class( );
			if (isset( $matches[ 1 ] ))
				O_Registry::set( "app/paginator/page", $matches[ 1 ] );
		} elseif (preg_match( "#tag(/([0-9]+))?/(.+)$#", $page, $matches )) {
			$class = $prefix . "Home";
			$cmd = new $class( );
			if (isset( $matches[ 2 ] ))
				O_Registry::set( "app/paginator/page", $matches[ 2 ] );
			$cmd->tag = $this->system->site->tags->test( "title", urldecode( $matches[ 3 ] ) )->getOne();
		}
		if (isset( $cmd ) && $cmd instanceof R_Lf_Command) {
			$cmd->instance = $this;
			return $cmd;
		}
		throw new O_Ex_PageNotFound( "Blog page not found", 404 );
	}

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