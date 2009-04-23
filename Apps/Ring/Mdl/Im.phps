<?php
/**
 * @table images
 *
 * @field:config system -inverse im
 */
class R_Mdl_Im extends R_Mdl_Site_SysInstance {
	const CREATIVE_CLASS = "R_Mdl_Im_Picture";

	/**
	 * Returns command instance to handle the request
	 *
	 * @param string $page
	 * @return R_Command
	 */
	public function getCommand( $page )
	{
		$prefix = "R_Lf_Sys_Blog_Cmd_";
		$matches = Array ();
		if ($page == "SystemAdmin") {
			$class = $prefix . "SystemAdmin";
			$cmd = new $class( );
		} elseif (preg_match( "#^form(/([0-9]*))?$#", $page, $matches )) {
			$class = $prefix . "Form";
			$cmd = new $class( );
			if (isset( $matches[ 2 ] ))
				$cmd->post_id = $matches[ 2 ];
		} elseif (preg_match( "#^([0-9]+)$#", $page, $matches )) {
			$class = $prefix . "Post";
			$cmd = new $class( );
			$cmd->post_id = $matches[ 1 ];
		} elseif (preg_match( "#^page-([0-9]+)$#", $page, $matches ) || $page == "Home") {
			$class = $prefix . "Home";
			$cmd = new $class( );
			if (isset( $matches[ 1 ] ))
				O_Registry::set( "app/paginator/page", $matches[ 1 ] );
		}
		if (isset( $cmd ) && $cmd instanceof R_Lf_Command) {
			$cmd->blog = $this;
			return $cmd;
		}
		throw new O_Ex_PageNotFound( "Blog page not found", 404 );
	}

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
}