<?php
/**
 * @table blog
 * @field site -has one R_Mdl_Site -inverse blogs
 * @field system -owns one R_Mdl_Site_System -inverse blog
 * @field posts -owns many R_Mdl_Blog_Post -inverse blog -order-by time DESC
 * @field anonces -owns many R_Mdl_Blog_Anonce -inverse blog -order-by time DESC
 * @field title tinytext -title Заголовок блога -edit -required Заголовок обязателен
 * @field perpage tinyint NOT NULL DEFAULT 15 -title Количество записей на страницу -edit -required Какое-то количество должно быть обязательно
 */
class R_Mdl_Blog extends O_Dao_ActiveRecord {

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

	public function url( $page = 1 )
	{
		return $this->system->url( $page > 1 ? "page-$page" : "" );
	}

	public function link() {
		return "<a href=\"".$this->url()."\">".$this->title."</a>";
	}


	public function getPosts()
	{
		return $this->setAccesses( $this->posts );
	}

	public function getAnonces()
	{
		return $this->setAccesses( $this->anonces );
	}

	public function getItem( $id )
	{
		return $this->getPosts()->test( "id", $id )->getOne();
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