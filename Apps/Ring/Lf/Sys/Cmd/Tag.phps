<?php
class R_Lf_Sys_Cmd_Tag extends R_Lf_Sys_Command {
	public $instance;
	public $tag;

	public function process()
	{
		$tpl = $this->getTemplate();
		$query = $this->tag->anonces->test("system", $this->instance->system->id);
		$tpl->title = $this->tag->title." - ".$this->instance->title;

		R_Mdl_Session::setQueryAccesses($query, $this->getSite());
		$tpl->paginator = $query->getPaginator(
				array ($this, "url"), $this->instance->perpage );

		$tpl->site = $this->getSite();
		$tpl->tag = $this->tag;
		$tpl->tags = $this->instance->system->{"anonces.tags"};
		return $tpl;
	}

	public function url($page) {
		return $this->tag->url($this->instance->system->urlbase, $page);
	}

	public function isAuthenticated()
	{
		return $this->tag && $this->instance && $this->can( "read " . $this->instance->system[ "access" ], $this->getSite() );
	}

}