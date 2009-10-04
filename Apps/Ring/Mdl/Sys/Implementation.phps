<?php
/**
 * @field system -owns one R_Mdl_Sys_Instance
 *
 * @field title -relative system->title
 * @field perpage TINYINT NOT NULL DEFAULT 15 -title Количество записей на страницу -edit -required Какое-то количество должно быть обязательно
 */
abstract class R_Mdl_Sys_Implementation extends O_Dao_ActiveRecord {

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
		// System admin
		if ($page == "SystemAdmin") {
			$class = $prefix . "SystemAdmin";
			$cmd = new $class( );
			// Creating/editing form
		} elseif (preg_match( "#^form(/([0-9]*))?$#", $page, $matches )) {
			$class = $prefix . "Form";
			$cmd = new $class( );
			if (isset( $matches[ 2 ] ))
				$cmd->creative_id = $matches[ 2 ];
			// Creative page
		} elseif (preg_match( "#^([0-9]+)$#", $page, $matches )) {
			$class = $prefix . "Creative";
			$cmd = new $class( );
			$cmd->creative_id = $matches[ 1 ];
			// List of creatives
		} elseif (preg_match( "#^page-([0-9]+)$#", $page, $matches ) || $page == "Home") {
			$class = $prefix . "Home";
			$cmd = new $class( );
			if (isset( $matches[ 1 ] ))
				O_Registry::set( "app/paginator/page", $matches[ 1 ] );
			// Creatives by tag
		} elseif (preg_match( "#tag(/([0-9]+))?/(.+)$#", $page, $matches )) {
			$class = $prefix . "Tag";
			$cmd = new $class( );
			if (isset( $matches[ 2 ] ))
				O_Registry::set( "app/paginator/page", $matches[ 2 ] );
			$cmd->tag = $this->system->site->tags->test( "title", urldecode( $matches[ 3 ] ) )->getOne();
			// Linked anonces
		} elseif (preg_match( "#^linked/([0-9]+)$#", $page, $matches )) {
			$class = $prefix . "Linked";
			$cmd = new $class( );
			$cmd->creative_id = $matches[ 1 ];
			// List of comments
		} elseif (preg_match( "#^comments(-([0-9]+))?$#", $page, $matches )) {
			$class = $prefix . "Comments";
			$cmd = new $class( );
			if (isset( $matches[ 2 ] ))
				O_Registry::set( "app/paginator/page", $matches[ 2 ] );
			// One collection page
		} elseif (preg_match( "#^coll-([0-9]+)$#", $page, $matches )) {
			$class = $prefix . "Collection";
			$cmd = new $class( );
			if (isset( $matches[ 1 ] ))
				$cmd->coll_id = $matches[ 1 ];
			// Collection edit page
		} elseif (preg_match( "#^coll-([0-9]+)/form$#", $page, $matches )) {
			$class = $prefix . "CollectionForm";
			$cmd = new $class( );
			if (isset( $matches[ 1 ] ))
				$cmd->coll_id = $matches[ 1 ];
			// Crosspostings
		} elseif (preg_match( "#^cross/([0-9]+)$#", $page, $matches )) {
			$class = $prefix . "Crosspost";
			$cmd = new $class( );
			$cmd->creative_id = $matches[ 1 ];
			// System RSS feed
		} elseif ("rss" == $page) {
			$class = $prefix . "RssFeed";
			$cmd = new $class( );
		}

		if (isset( $cmd ) && $cmd instanceof R_Lf_Command) {
			$cmd->instance = $this;
			return $cmd;
		}
		throw new O_Ex_PageNotFound( "System page not found", 404 );
	}

	/**
	 * Returns one creative by its id, if it's in this system instance and can be viewed
	 *
	 * @param int $id
	 * @return R_Mdl_Sys_Creative
	 */
	public function getCreative( $id )
	{
		$class = constant( get_class( $this ) . "::CREATIVE_CLASS" );
		if (!$class)
			throw new O_Ex_Critical( "::CREATIVE_CLASS not defined" );
		return $this->getCreativeById( $id, $class );
	}

	/**
	 * Returns creative by id and class
	 *
	 * @param int $id
	 * @param string $class
	 * @return R_Mdl_Sys_Creative
	 */
	protected function getCreativeById( $id, $class )
	{
		$item = O_Dao_ActiveRecord::getById( $id, $class );
		if (!$item instanceof R_Mdl_Sys_Creative)
			return false;
		if ($item->anonce->system->instance != $this)
			return false;
		if (!$item->anonce->isVisible())
			return false;
		return $item;
	}

	public function url( $page = 1 )
	{
		return $this->system->url( $page > 1 ? "page-$page" : "" );
	}

	public function addFormTitle()
	{
		return "Новая запись";
	}

}