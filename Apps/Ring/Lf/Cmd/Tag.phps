<?php
class R_Lf_Cmd_Tag extends R_Lf_Command {
	private $tag;

	public function process()
	{
		$this->tag = $this->getSite()->tags->test("title", O_Registry::get("app/current/tag"))->getOne();
		if(!$this->tag) throw new O_Ex_PageNotFound("Tag not found.", 404);

		$tpl = $this->getTemplate();
		$tpl->tag = $this->tag;
		$tpl->tags = $this->getSite()->tags->test("weight", 0, ">")->limit(100);
		$anonces = $this->tag->anonces;
		R_Mdl_Session::setQueryAccesses($anonces, $this->getSite());
		$tpl->pager = $anonces->getPaginator(array($this, "url"));
		return $tpl;
	}

	public function url($page) {
		return $this->tag->url("", $page);
	}


}