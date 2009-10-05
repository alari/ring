<?php
class R_Lf_Sys_Cmd_Collection extends R_Lf_Sys_Command {
	
	public $collection;
	public $coll_id;

	public function process()
	{
		$tpl = $this->getTemplate();
		$tpl->collection = $this->collection;
		return $tpl;
	}

	public function isAuthenticated()
	{
		if (!$this->instance)
			throw new O_Ex_PageNotFound( "Instance not found.", 404 );
		$this->collection = $this->instance->system->collections->test( "id", $this->coll_id )->getOne();
		if (!$this->collection)
			throw new O_Ex_PageNotFound( "Collection not found.", 404 );
		return $this->can( "read " . $this->instance->system[ "access" ], $this->getSite() );
	}

}