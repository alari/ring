<?php
class R_Lf_Sys_Cmd_Home extends R_Lf_Sys_Command {
	public $instance;
	public $tag;

	public function process()
	{
		$tpl = $this->getTemplate();
		try {
			if($this->tag) {
				$query = $this->tag->anonces->test("system", $this->instance->system->id);
			} else {
				$query = $this->instance->system->anonces;
			}
			$tpl->paginator = new O_Dao_Paginator( $query,
					array ($this, "url"), $this->instance->perpage );
		}
		catch (O_Ex_PageNotFound $e) {

		}
		$tpl->site = $this->getSite();
		$tpl->title = $this->instance->title;
		$tpl->tag = $this->tag;
		$tpl->tags = $this->instance->system->site->tags->test("weight", 0, ">");
		return $tpl;
	}

	public function url($page) {
		return $this->tag ? $this->tag->url($this->instance->system->urlbase.($page>1?"/".$page:"")): $this->blog->url($page);
	}


	public function isAuthenticated()
	{
		return $this->instance && $this->can( "read " . $this->instance->system[ "access" ], $this->getSite() );
	}

}