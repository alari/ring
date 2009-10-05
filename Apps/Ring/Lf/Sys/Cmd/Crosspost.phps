<?php
class R_Lf_Sys_Cmd_Crosspost extends R_Lf_Sys_Command {

	public function process()
	{
		if ($this->getParam( "d" )) {
			$cp = $this->creative->anonce->crossposts[ $this->getParam( "d" ) ];
			if ($cp)
				$cp->delete();
			return $this->redirect();
		}
		$ids = $this->creative->anonce->crossposts->field( "service" );
		$available_services = $this->getSite()->crosspost_services->test( "id", $ids, 
				O_Db_Query::NOT_IN );
		if ($this->getParam( "a" )) {
			$serv = $available_services[ $this->getParam( "a" ) ];
			if ($serv) {
				new R_Mdl_Site_Crosspost( $this->creative->anonce, $serv );
				$this->setNotice( "Кросспост добавлен в очередь" );
			}
			return $this->redirect();
		}
		
		$tpl = $this->getTemplate();
		$tpl->creative = $this->creative;
		$tpl->tags = $this->creative->tags;
		$tpl->crossposts = $this->creative->anonce->crossposts;
		$tpl->available_services = $available_services;
		return $tpl;
	}

	public function isAuthenticated()
	{
		if (!$this->instance)
			throw new O_Ex_PageNotFound( "instance not found", 404 );
		$this->creative = $this->instance->getCreative( $this->creative_id );
		if (!$this->creative)
			throw new O_Ex_PageNotFound( "creative not found", 404 );
		return $this->instance && $this->can( "read " . $this->instance->system[ "access" ], 
				$this->creative->anonce ) && $this->can( "crosspost", $this->getSite() );
	}

}